<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\RentalItem;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate tables to ensure a clean slate, especially for development
        // You might want to disable this in production
        // Customer::truncate();
        // Product::truncate();
        // Rental::truncate();
        // RentalItem::truncate();

        // Create sample products
        $p1 = Product::create([
            'product_code' => 'VA001', 'name' => 'Váy dạ hội đen', 'rental_price' => 300000, 
            'deposit_price' => 200000,
            'purchase_price' => 1500000, 'purchase_date' => '2024-01-15', 'status' => 'available'
        ]);
        $p2 = Product::create([
            'product_code' => 'VA002', 'name' => 'Váy cưới trắng', 'rental_price' => 500000, 
            'deposit_price' => 300000,
            'purchase_price' => 2500000, 'purchase_date' => '2024-01-20', 'status' => 'available'
        ]);
        $p3 = Product::create([
            'product_code' => 'VA003', 'name' => 'Váy cocktail đỏ', 'rental_price' => 150000, 
            'deposit_price' => 100000,
            'purchase_price' => 800000, 'purchase_date' => '2024-02-01', 'status' => 'available'
        ]);
         $p4 = Product::create([
            'product_code' => 'VA004', 'name' => 'Váy dự tiệc xanh', 'rental_price' => 250000, 
            'deposit_price' => 150000,
            'purchase_price' => 1200000, 'purchase_date' => '2024-02-10', 'status' => 'available'
        ]);

        // Create sample customers
        $c1 = Customer::create(['name' => 'Nguyễn Thị Anh', 'phone' => '0901234567']);
        $c2 = Customer::create(['name' => 'Trần Văn Bình', 'phone' => '0912345678']);
        
        // --- Create Sample Rentals (New Structure) ---

        // Rental 1: Customer 1 rents 2 products (p1, p3)
        $rental1 = Rental::create([
            'customer_id' => $c1->id,
            'rental_date' => '2024-06-01',
            'expected_return_date' => '2024-06-08',
            'total_price' => $p1->rental_price + $p3->rental_price,
            'rental_fee' => $p1->rental_price + $p3->rental_price,
            'deposit_amount' => $p1->deposit_price + $p3->deposit_price,
            'deposit_type' => 'money',
            'total_paid' => ($p1->rental_price + $p3->rental_price) + ($p1->deposit_price + $p3->deposit_price),
            'refund_amount' => $p1->deposit_price + $p3->deposit_price,
            'status' => 'active',
            'notes' => 'Thuê 2 váy cho sự kiện'
        ]);
        // Add items to rental 1
        $rental1->items()->create(['product_id' => $p1->id, 'price' => $p1->rental_price]);
        $rental1->items()->create(['product_id' => $p3->id, 'price' => $p3->rental_price]);
        // Update product statuses
        $p1->update(['status' => 'rented']);
        $p3->update(['status' => 'rented']);


        // Rental 2: Customer 2 rents 1 product (p2) and has returned it
        $rental2 = Rental::create([
            'customer_id' => $c2->id,
            'rental_date' => '2024-05-20',
            'expected_return_date' => '2024-05-27',
            'actual_return_date' => '2024-05-26',
            'total_price' => $p2->rental_price,
            'rental_fee' => $p2->rental_price,
            'deposit_amount' => $p2->deposit_price,
            'deposit_type' => 'money',
            'total_paid' => $p2->rental_price + $p2->deposit_price,
            'refund_amount' => $p2->deposit_price,
            'status' => 'returned',
            'notes' => 'Đã trả sớm'
        ]);
        // Add item to rental 2
        $rental2->items()->create(['product_id' => $p2->id, 'price' => $p2->rental_price]);
        // Product p2 is available because it was returned
        $p2->update(['status' => 'available']);
    }
}
