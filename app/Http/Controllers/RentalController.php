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
            'total_price' => 'required|numeric|min:0',
            'deposit_type' => 'required|in:money,idcard',
            'deposit_money' => 'nullable|numeric|min:0',
            'deposit_idcard' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Xác định giá trị cọc
        $depositType = $validated['deposit_type'];
        $depositValue = null;
        if ($depositType === 'money') {
            $depositValue = $validated['deposit_money'] ?? '0';
        } elseif ($depositType === 'idcard') {
            $depositValue = $validated['deposit_idcard'] ?? '';
        }

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

            // 3. Create the Rental
            $rental = Rental::create([
                'customer_id' => $customer->id,
                'rental_date' => $validated['rental_date'],
                'expected_return_date' => $validated['expected_return_date'],
                'total_price' => $validated['total_price'],
                'deposit_type' => $depositType,
                'deposit_value' => $depositValue,
                'notes' => $validated['notes'],
                'status' => 'active',
            ]);

            // 4. Create Rental Items and Update Product Status
            foreach ($productsToRent as $product) {
                $rental->items()->create([
                    'product_id' => $product->id,
                    'price' => $product->rental_price,
                ]);
                $product->update(['status' => 'rented']);
            }

            DB::commit();

            return redirect()->route('rentals.index')
                ->with('success', 'Đơn thuê đã được tạo thành công!');

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

            // Update rental status
            $rental->update([
                'actual_return_date' => now(),
                'status' => 'returned',
            ]);

            // Update status for all products in the rental
            foreach ($rental->products as $product) {
                $product->update(['status' => 'available']);
            }

            DB::commit();

            return redirect()->route('rentals.index')
                ->with('success', 'Đã trả hàng thành công cho đơn thuê #' . $rental->id);

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
} 