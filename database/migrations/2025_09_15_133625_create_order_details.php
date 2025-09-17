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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'guest_id')->nullable();
            $table->string(column: 'user_id')->nullable();
            $table->string(column: 'order_status')->nullable();
            $table->string(column: 'order_type')->nullable();
            $table->string(column: 'card_type')->nullable();
            $table->string(column: 'transaction_id')->nullable();
            $table->string(column: 'auth_code')->nullable();
            $table->string(column: 'response_code')->nullable();
            $table->string(column: 'response_desc')->nullable();
            $table->string(column: 'payment_response')->nullable();
            $table->string(column: 'total_amount')->nullable();
            $table->string(column: 'fname')->nullable();
            $table->string(column: 'lname')->nullable();
            $table->string(column: 'username')->nullable();
            $table->string(column: 'email')->nullable();
            $table->string(column: 'mobile')->nullable();
            $table->string(column: 'address1')->nullable();
            $table->string(column: 'address2')->nullable();
            $table->string(column: 'country')->nullable();
            $table->string(column: 'state')->nullable();
            $table->string(column: 'zip')->nullable();
            $table->string(column: 's_fname')->nullable();
            $table->string(column: 's_lname')->nullable();
            $table->string(column: 's_username')->nullable();
            $table->string(column: 's_email')->nullable();
            $table->string(column: 's_mobile')->nullable();
            $table->string(column: 's_address1')->nullable();
            $table->string(column: 's_address2')->nullable();
            $table->string(column: 's_country')->nullable();
            $table->string(column: 's_state')->nullable();
            $table->string(column: 's_zip')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
