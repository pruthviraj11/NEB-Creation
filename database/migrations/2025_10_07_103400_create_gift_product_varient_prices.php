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
        Schema::create('gift_product_varient_prices', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('gift_product_id')->nullable();
                $table->string('gift_varient_id')->nullable();
                $table->string('price')->nullable();
                $table->boolean('status')->default(1);
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('gift_product_id')
              ->references('id')
              ->on('gift_products')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_product_varient_prices');
    }
};
