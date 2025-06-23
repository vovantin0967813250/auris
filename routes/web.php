<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RentalController;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Products management
Route::resource('products', ProductController::class);
Route::get('products/search/code', [ProductController::class, 'searchByCode'])->name('products.searchByCode');

// Rentals management
Route::get('rentals/history', [RentalController::class, 'history'])->name('rentals.history');
Route::get('rentals/product/info', [RentalController::class, 'getProductInfo'])->name('rentals.getProductInfo');
Route::resource('rentals', RentalController::class)->except(['edit', 'update', 'destroy']);
Route::post('rentals/{rental}/return', [RentalController::class, 'return'])->name('rentals.return');
