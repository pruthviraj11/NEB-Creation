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
        Schema::table('order_details', function (Blueprint $table) {
            $table->decimal('original_amount', 10, 2)->nullable()->after('total_amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('original_amount');
            $table->string('promo_code')->nullable()->after('discount_amount');
            $table->string('coupon_id')->nullable()->after('promo_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['original_amount', 'discount_amount', 'promo_code', 'coupon_id']);
        });
    }
};
