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
        Schema::table('temp_carts', function (Blueprint $table) {
            $table->string(column: 'is_creative_art')->nullable()->after('total_amount');
            $table->string(column: 'creative_info')->nullable()->after('is_creative_art');
            $table->string(column: 'is_bulk_purchase')->nullable()->after('creative_info');
            $table->string(column: 'bulk_info')->nullable()->after('is_bulk_purchase');
            $table->string(column: 'extra_bulk')->nullable()->after('bulk_info');
            $table->string(column: 'is_canvas')->nullable()->after('extra_bulk');
            $table->string(column: 'is_gift_product')->nullable()->after('is_canvas');
            $table->string(column: 'gift_product_id')->nullable()->after('is_gift_product');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temp_carts', function (Blueprint $table) {
            //
        });
    }
};
