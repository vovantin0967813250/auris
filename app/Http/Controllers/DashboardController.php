<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Rental;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalProducts = Product::count();
        $availableProducts = Product::available()->count();
        $rentedProducts = Product::rented()->count();
        $totalCustomers = Customer::count();
        $activeRentals = Rental::active()->count();
        $overdueRentals = Rental::overdue()->count();

        // Recent activities
        $recentRentals = Rental::with(['products', 'customer'])
            ->latest()
            ->take(5)
            ->get();

        $recentProducts = Product::latest()
            ->take(5)
            ->get();

        // Revenue calculation (this month) - only rental fees, not deposits
        $thisMonthRevenue = Rental::where('status', 'returned')
            ->whereMonth('actual_return_date', now()->month)
            ->whereYear('actual_return_date', now()->year)
            ->sum('rental_fee');

        return view('dashboard', compact(
            'totalProducts',
            'availableProducts',
            'rentedProducts',
            'totalCustomers',
            'activeRentals',
            'overdueRentals',
            'recentRentals',
            'recentProducts',
            'thisMonthRevenue'
        ));
    }
} 