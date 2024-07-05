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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lname')->nullable();//เพิ่ม
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_admin')->nullable();//เพิ่ม
            $table->string('phone')->nullable();//เพิ่ม
            $table->date('start_date')->nullable();//เพิ่ม
            $table->date('birthday')->nullable();//เพิ่ม
            $table->text('address')->nullable();//เพิ่ม
            $table->string('image')->nullable();//เพิ่ม
            $table->boolean('status')->nullable(); //เพิ่ม
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
