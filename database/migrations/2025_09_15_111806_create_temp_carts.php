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
        Schema::create('temp_carts', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'guest_id')->nullable();
            $table->string(column: 'user_id')->nullable();
            $table->string(column: 'photo_id')->nullable();
            $table->string(column: 'quntity')->nullable();
            $table->string(column: 'amount')->nullable();
            $table->string(column: 'total_amount')->nullable();
            $table->enum('order_status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_carts');
    }
};
