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
            $table->string('deposit_type')->default('money')->after('rental_price'); // e.g., 'money' or 'id_card'
            $table->string('deposit_id_card_info')->nullable()->after('deposit_type');
            $table->decimal('deposit', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['deposit_type', 'deposit_id_card_info']);
            $table->decimal('deposit', 10, 2)->default(0)->change();
        });
    }
};
