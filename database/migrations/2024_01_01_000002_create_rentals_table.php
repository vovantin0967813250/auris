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
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('rental_date'); // Ngày thuê
            $table->date('expected_return_date'); // Ngày dự kiến trả
            $table->date('actual_return_date')->nullable(); // Ngày trả thực tế
            $table->decimal('total_price', 10, 2)->default(0);
            $table->decimal('rental_fee', 10, 2)->default(0); // Tiền thuê
            $table->decimal('deposit_amount', 10, 2)->default(0); // Tiền cọc
            $table->string('deposit_type')->nullable(); // Loại cọc (money/idcard)
            $table->string('deposit_note')->nullable(); // Ghi chú về cọc (số CMND, etc.)
            $table->decimal('total_paid', 10, 2)->default(0); // Tổng tiền khách đã trả
            $table->decimal('refund_amount', 10, 2)->default(0); // Số tiền hoàn lại
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