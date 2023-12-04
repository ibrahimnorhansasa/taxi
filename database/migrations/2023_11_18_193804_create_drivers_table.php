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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('firstname');
            $table->string('fathername')->nullable();
            $table->string('lastname');
            $table->string('phone');
            $table->double('balance')->default(0);
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('car_number');
            $table->string('car_type');
            $table->string('car_color');
            $table->tinyInteger('is_active')->default(0);
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
