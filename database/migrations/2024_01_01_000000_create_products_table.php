<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique(); // Mã sản phẩm
            $table->string('name'); // Tên sản phẩm
            $table->text('description')->nullable(); // Mô tả
            $table->string('image')->nullable(); // Hình ảnh
            $table->decimal('purchase_price', 10, 2); // Giá mua về
            $table->decimal('rental_price', 10, 2); // Giá cho thuê
            $table->decimal('deposit_price', 10, 2)->default(0); // Giá cọc mặc định
            $table->date('purchase_date'); // Ngày mua về
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available'); // Trạng thái
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}; 