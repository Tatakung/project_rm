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
        Schema::create('financials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_detail_id')->nullable(); //FKorder_detail_id
            $table->unsignedBigInteger('cost_id')->nullable(); 
            $table->string('item_name')->nullable();
            $table->boolean('type_order')->nullable(); // 1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4เช่าตัด
            $table->decimal('financial_income', 8, 2)->nullable(); 
            $table->decimal('financial_expenses', 8, 2)->nullable(); 
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('order_detail_id')->references('id')->on('orderdetails');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financials');
    }
};
