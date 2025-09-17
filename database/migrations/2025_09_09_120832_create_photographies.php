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
        Schema::create('photographies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->integer(column: 'parent_id')->nullable();
            $table->string(column: 'title')->nullable();
            $table->string(column: 'slug')->nullable();
            $table->string(column: 'front_image')->nullable();
            $table->string(column: 'back_image')->nullable();
            $table->string(column: 'price')->nullable();
            $table->string(column: 'discount_price')->nullable();
            $table->longText(column: 'description')->nullable();
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
        Schema::dropIfExists('photographies');
    }
};
