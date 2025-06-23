<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Add new columns for order summary
            $table->decimal('total_price', 15, 2)->after('expected_return_date');
            $table->decimal('deposit_amount', 15, 2)->nullable()->after('total_price');

            // Drop columns related to a single product rental
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            $table->dropColumn('rental_price');
            $table->dropColumn('deposit_type');
            $table->dropColumn('deposit');
            $table->dropColumn('deposit_id_card_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Re-add the columns if we need to roll back the migration
            $table->foreignId('product_id')->after('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('rental_price', 10, 2)->after('expected_return_date');
            $table->string('deposit_type')->default('money')->after('rental_price');
            $table->decimal('deposit', 10, 2)->nullable()->after('deposit_type');
            $table->string('deposit_id_card_info')->nullable()->after('deposit');

            // Drop the new columns
            $table->dropColumn('total_price');
            $table->dropColumn('deposit_amount');
        });
    }
};
