<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('testimonials', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('designation')->nullable();
      $table->text('message')->nullable();
      $table->tinyInteger('star')->default(5); // Rating 1-5
      $table->string('profile_pic')->nullable(); // store file path
      $table->boolean('status')->default(1); // 1 = active
      $table->softDeletes();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('testimonials');
  }
};
