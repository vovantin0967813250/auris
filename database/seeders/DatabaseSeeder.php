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
        // Tạo tài khoản admin
        \App\Models\User::updateOrCreate(
            [
                'name' => 'admin',
            ],
            [
                'name' => 'admin',
                'email' => 'admin@auris.test',
                'password' => bcrypt('1999'),
            ]
        );
    }
}
