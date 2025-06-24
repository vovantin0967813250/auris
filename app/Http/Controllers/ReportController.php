<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Bộ lọc thời gian
        $from = $request->input('from') ? Carbon::parse($request->input('from'))->startOfDay() : null;
        $to = $request->input('to') ? Carbon::parse($request->input('to'))->endOfDay() : null;

        // Query rentals theo filter
        $rentalQuery = Rental::query();
        if ($from) $rentalQuery->where('rental_date', '>=', $from);
        if ($to) $rentalQuery->where('rental_date', '<=', $to);

        // Doanh thu (chỉ tính tiền thuê, đã trả)
        $revenue = (clone $rentalQuery)->where('status', 'returned')->sum('rental_fee');
        // Số đơn thuê
        $rentalCount = (clone $rentalQuery)->count();
        // Số sản phẩm
        $productCount = Product::count();
        // Số khách hàng
        $customerCount = Customer::count();
        // Số đơn quá hạn
        $overdueCount = Rental::overdue()->count();
        // Tổng tiền phạt
        $lateRentals = Rental::where('status', 'returned')->get();
        $totalLateFee = $lateRentals->sum(function($rental) {
            return $rental->getLateFee();
        });

        // Doanh thu theo tháng (12 tháng gần nhất) - dùng strftime cho SQLite
        $monthlyRevenue = Rental::where('status', 'returned')
            ->whereBetween('actual_return_date', [now()->subMonths(11)->startOfMonth(), now()->endOfMonth()])
            ->selectRaw(
                "strftime('%Y', actual_return_date) as year, strftime('%m', actual_return_date) as month, SUM(rental_fee) as total"
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Top 5 sản phẩm thuê nhiều nhất
        $topProducts = Product::withCount('rentalItems')
            ->orderByDesc('rental_items_count')
            ->take(5)
            ->get();
        // Top 5 sản phẩm thuê ít nhất (có thể lọc sản phẩm đã từng được thuê)
        $leastProducts = Product::withCount('rentalItems')
            ->orderBy('rental_items_count')
            ->take(5)
            ->get();

        // Top 5 khách hàng thuê nhiều nhất
        $topCustomers = Customer::withCount(['rentals' => function($q) {
            $q->where('status', 'returned');
        }])
        ->orderByDesc('rentals_count')
        ->take(5)
        ->get();

        // Đơn thuê đang hoạt động
        $activeRentals = Rental::with(['customer', 'products'])
            ->where('status', 'active')
            ->orderBy('expected_return_date')
            ->get();
        // Đơn thuê quá hạn
        $overdueRentals = Rental::with(['customer', 'products'])
            ->overdue()
            ->orderBy('expected_return_date')
            ->get();

        return view('reports.index', compact(
            'revenue',
            'rentalCount',
            'productCount',
            'customerCount',
            'overdueCount',
            'totalLateFee',
            'from',
            'to',
            'monthlyRevenue',
            'topProducts',
            'leastProducts',
            'topCustomers',
            'activeRentals',
            'overdueRentals'
        ));
    }
} 