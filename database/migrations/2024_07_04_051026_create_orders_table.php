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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); //FKพนักงาน
            $table->unsignedBigInteger('customer_id')->nullable(); //FKลูกค้า
            $table->integer('total_quantity')->nullable(); 
            $table->decimal('total_price', 8, 2)->nullable();
            $table->decimal('total_deposit', 8, 2)->nullable();
            $table->boolean('order_status')->nullable();
            $table->timestamps();
            $table->softDeletes();

            //กำหนด Foreign Key ให้ user_id อ้างอิงไปยังตาราง users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //กำหนด Foreign Key ให้ customer_id อ้างอิงไปยังตาราง customers
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
