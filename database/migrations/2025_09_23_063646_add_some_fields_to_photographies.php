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
        Schema::table('photographies', function (Blueprint $table) {
           $table->enum('is_richard_photo', ['Yes', 'No'])->default('No')->after('is_home');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photographies', function (Blueprint $table) {
            //
        });
    }
};
