<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('rental_date'); // Ngày thuê
            $table->date('expected_return_date'); // Ngày dự kiến trả
            $table->date('actual_return_date')->nullable(); // Ngày trả thực tế
            $table->decimal('rental_price', 10, 2); // Giá thuê
            $table->decimal('deposit', 10, 2)->default(0); // Tiền cọc
            $table->enum('status', ['active', 'returned', 'overdue'])->default('active'); // Trạng thái
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
}; 