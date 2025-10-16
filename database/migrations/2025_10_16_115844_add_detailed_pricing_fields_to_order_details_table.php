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
            $table->decimal('product_total', 10, 2)->nullable()->after('total_amount');
            $table->decimal('delivery_charge', 10, 2)->default(5.00)->after('product_total');
            $table->decimal('tax_rate', 5, 2)->default(9.25)->after('delivery_charge');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('tax_rate');
            $table->decimal('total_before_discount', 10, 2)->nullable()->after('tax_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn([
                'product_total', 
                'delivery_charge', 
                'tax_rate', 
                'tax_amount', 
                'total_before_discount'
            ]);
        });
    }
};
