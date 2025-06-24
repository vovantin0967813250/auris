<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    // Display rental page
    public function index()
    {
        $activeRentals = Rental::with(['customer', 'products'])
            ->where('status', 'active')
            ->latest()
            ->paginate(10);
            
        $overdueRentals = Rental::with('customer')
            ->overdue()
            ->get();

        return view('rentals.index', compact('activeRentals', 'overdueRentals'));
    }

    // Show rental form
    public function create()
    {
        return view('rentals.create');
    }

    // Store new rental
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'rental_date' => 'required|date',
            'expected_return_date' => 'required|date|after_or_equal:rental_date',
            'products' => 'required|array|min:1',
            'products.*' => 'required|integer|exists:products,id',
            'rental_fee' => 'required|numeric|min:0', // Tiền thuê
            'deposit_type' => 'required|in:money,idcard',
            'deposit_amount' => 'nullable|numeric|min:0', // Tiền cọc
            'deposit_note' => 'nullable|string|max:255', // Ghi chú cọc (số CMND)
            'notes' => 'nullable|string',
        ]);

        // Xác định giá trị cọc
        $depositType = $validated['deposit_type'];
        $depositAmount = 0;
        $depositNote = null;
        
        if ($depositType === 'money') {
            $depositAmount = $validated['deposit_amount'] ?? 0;
        } elseif ($depositType === 'idcard') {
            $depositNote = $validated['deposit_note'] ?? '';
        }

        // Tính tổng tiền khách phải trả
        $totalPaid = $validated['rental_fee'] + $depositAmount;

        try {
            DB::beginTransaction();

            // 1. Find or Create Customer
            $customer = Customer::firstOrCreate(
                ['phone' => $validated['customer_phone']],
                ['name' => $validated['customer_name']]
            );

            // 2. Check Product Availability
            $productsToRent = Product::find($validated['products']);
            foreach ($productsToRent as $product) {
                if (!$product->isAvailable()) {
                    throw new \Exception("Sản phẩm '{$product->name}' hiện không có sẵn.");
                }
            }

            // 3. Calculate total price (sum of all product rental prices)
            $totalPrice = $productsToRent->sum('rental_price');

            // 4. Create the Rental
            $rental = Rental::create([
                'customer_id' => $customer->id,
                'rental_date' => $validated['rental_date'],
                'expected_return_date' => $validated['expected_return_date'],
                'total_price' => $totalPrice,
                'rental_fee' => $validated['rental_fee'],
                'deposit_amount' => $depositAmount,
                'deposit_type' => $depositType,
                'deposit_note' => $depositNote,
                'total_paid' => $totalPaid,
                'refund_amount' => $depositAmount, // Số tiền sẽ hoàn lại
                'notes' => $validated['notes'],
                'status' => 'active',
            ]);

            // 5. Create Rental Items and Update Product Status
            foreach ($productsToRent as $product) {
                $rental->items()->create([
                    'product_id' => $product->id,
                    'price' => $product->rental_price,
                ]);
                $product->update(['status' => 'rented']);
            }

            DB::commit();

            return redirect()->route('rentals.index')
                ->with('success', 'Đơn thuê đã được tạo thành công! Tổng tiền: ' . number_format($totalPaid) . ' VNĐ');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Return all products in a rental
    public function return(Rental $rental)
    {
        if ($rental->status !== 'active') {
            return back()->with('error', 'Đơn thuê này đã được xử lý!');
        }

        try {
            DB::beginTransaction();

            // Tính số ngày trễ và tiền phạt
            $actualReturnDate = now();
            $lateDays = 0;
            $lateFee = 0;
            $refundMessage = '';

            // Cập nhật ngày trả thực tế
            $rental->actual_return_date = $actualReturnDate;
            $rental->status = 'returned';

            // Tính phạt nếu có
            $lateDays = $rental->getLateDays();
            $lateFee = $rental->getLateFee();

            if ($lateDays > 0 && $lateFee > 0) {
                if ($rental->hasMoneyDeposit()) {
                    // Trừ vào tiền cọc
                    $refund = $rental->deposit_amount - $lateFee;
                    $rental->refund_amount = $refund > 0 ? $refund : 0;
                    $refundMessage = 'Khách trả trễ ' . $lateDays . ' ngày, đã trừ ' . number_format($lateFee) . ' VNĐ vào tiền cọc. Số tiền hoàn lại: ' . number_format($rental->refund_amount) . ' VNĐ.';
                } elseif ($rental->hasIdCardDeposit()) {
                    // Báo khách đóng thêm
                    $refundMessage = 'Khách trả trễ ' . $lateDays . ' ngày, vui lòng thu thêm ' . number_format($lateFee) . ' VNĐ tiền phạt.';
                }
            } else {
                // Không trễ hạn
                if ($rental->hasMoneyDeposit()) {
                    $refundMessage = 'Hoàn lại cọc: ' . number_format($rental->deposit_amount) . ' VNĐ';
                } elseif ($rental->hasIdCardDeposit()) {
                    $refundMessage = 'Hoàn lại CMND: ' . $rental->deposit_note;
                }
            }

            $rental->save();

            // Update status for all products in the rental
            foreach ($rental->products as $product) {
                $product->update(['status' => 'available']);
            }

            DB::commit();

            return redirect()->route('rentals.index')
                ->with('success', 'Đã trả hàng thành công cho đơn thuê #' . $rental->id . '. ' . $refundMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Show rental details
    public function show(Rental $rental)
    {
        $rental->load(['customer', 'products']);
        return view('rentals.show', compact('rental'));
    }

    // Get product info for rental form
    public function getProductInfo(Request $request)
    {
        $productCode = $request->get('product_code');
        $product = Product::where('product_code', $productCode)->first();

        if (!$product) {
            return response()->json(['error' => 'Không tìm thấy sản phẩm'], 404);
        }

        if (!$product->isAvailable()) {
            return response()->json(['error' => 'Sản phẩm đang được cho thuê.'], 400);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'rental_price' => $product->rental_price,
            'image' => $product->image ? asset('storage/' . $product->image) : null,
        ]);
    }

    // Display rental history page
    public function history(Request $request)
    {
        $query = Rental::with(['customer', 'products'])->latest('rental_date');

        if ($request->has('search') && $request->input('search')) {
            $searchTerm = $request->input('search');
            $query->whereHas('customer', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        $rentals = $query->paginate(15)->withQueryString();

        return view('rentals.history', compact('rentals'));
    }

    // Extend rental duration
    public function extend(Request $request, Rental $rental)
    {
        if ($rental->status !== 'active') {
            return back()->with('error', 'Chỉ có thể gia hạn đơn thuê đang hoạt động!');
        }

        $validated = $request->validate([
            'extension_days' => 'required|integer|min:1|max:30',
        ]);

        try {
            DB::beginTransaction();

            $extensionDays = (int) $validated['extension_days'];
            $oldExpectedReturnDate = $rental->expected_return_date->copy();
            $newExpectedReturnDate = $oldExpectedReturnDate->addDays($extensionDays);

            // Tính tiền thuê bổ sung theo logic hiện tại
            $additionalRentalFee = $this->calculateAdditionalRentalFee($rental, $extensionDays);

            // Cập nhật đơn thuê
            $rental->expected_return_date = $newExpectedReturnDate;
            $rental->rental_fee += $additionalRentalFee;
            $rental->total_paid += $additionalRentalFee;
            $rental->save();

            DB::commit();

            return redirect()->route('rentals.show', $rental)
                ->with('success', "Đã gia hạn thành công thêm {$extensionDays} ngày. Tiền thuê bổ sung: " . number_format($additionalRentalFee) . " VNĐ. Ngày trả mới: " . $newExpectedReturnDate->format('d/m/Y'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Calculate additional rental fee for extension
    private function calculateAdditionalRentalFee(Rental $rental, int $extensionDays)
    {
        $totalAdditionalFee = 0;
        $currentRentalDays = $rental->rental_date->diffInDays($rental->expected_return_date);
        $productCount = $rental->items->count();

        // Tính tiền gia hạn chỉ cho các ngày mới, nhân với số lượng sản phẩm
        for ($day = 1; $day <= $extensionDays; $day++) {
            $dayNumber = $currentRentalDays + $day;
            $dailyFee = 0;

            // Lấy giá thuê trung bình của từng sản phẩm (giả sử các sản phẩm có giá giống nhau)
            // Nếu khác nhau, cần tính riêng từng sản phẩm
            foreach ($rental->items as $item) {
                $basePrice = $item->product->rental_price;
                if ($dayNumber == 1) {
                    $dailyFee += $basePrice;
                } elseif ($dayNumber == 2) {
                    $dailyFee += $basePrice + 20000;
                } else {
                    $dailyFee += $basePrice + 20000 + (($dayNumber - 2) * 10000);
                }
            }
            $totalAdditionalFee += $dailyFee;
        }
        return $totalAdditionalFee;
    }
} 