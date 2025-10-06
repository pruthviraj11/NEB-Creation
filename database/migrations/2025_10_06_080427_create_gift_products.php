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
        Schema::create('gift_products', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'product_name')->nullable();
            $table->string(column: 'product_image')->nullable();
            $table->string(column: 'product_varient')->nullable();
            $table->string(column: 'product_price')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_products');
    }
};
