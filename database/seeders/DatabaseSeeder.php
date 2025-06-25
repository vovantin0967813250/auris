<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chỉ tạo sản phẩm mẫu
        Product::create([
            'product_code' => 'VA001', 'name' => 'Váy dạ hội đen', 'rental_price' => 300000, 
            'deposit_price' => 200000,
            'purchase_price' => 1500000, 'purchase_date' => '2024-01-15', 'status' => 'available'
        ]);
        Product::create([
            'product_code' => 'VA002', 'name' => 'Váy cưới trắng', 'rental_price' => 500000, 
            'deposit_price' => 300000,
            'purchase_price' => 2500000, 'purchase_date' => '2024-01-20', 'status' => 'available'
        ]);
        Product::create([
            'product_code' => 'VA003', 'name' => 'Váy cocktail đỏ', 'rental_price' => 150000, 
            'deposit_price' => 100000,
            'purchase_price' => 800000, 'purchase_date' => '2024-02-01', 'status' => 'available'
        ]);
        Product::create([
            'product_code' => 'VA004', 'name' => 'Váy dự tiệc xanh', 'rental_price' => 250000, 
            'deposit_price' => 150000,
            'purchase_price' => 1200000, 'purchase_date' => '2024-02-10', 'status' => 'available'
        ]);

        // Tạo tài khoản admin
        User::updateOrCreate(
            [
                'name' => 'admin',
                'password' => bcrypt('1999'),
            ]
        );
    }
}
