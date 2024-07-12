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
        Schema::create('orderdetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jewelry_id')->nullable(); //FKเครื่องประดับ
            $table->unsignedBigInteger('dress_id')->nullable(); // FKชุด
            $table->unsignedBigInteger('order_id')->nullable(); //FKออเดอร์
            $table->unsignedBigInteger('employee_id')->nullable(); // ไม่มีความสัมพันธ์นะ
            $table->string('title_name')->nullable();
            $table->decimal('late_charge', 8, 2)->nullable(); 
            $table->date('real_pickup_date')->nullable(); 
            $table->date('real_return_date')->nullable(); 
            $table->date('pickup_date')->nullable(); 
            $table->date('return_date')->nullable(); 
            $table->string('type_dress')->nullable();
            $table->boolean('type_order')->nullable(); // 1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4เช่าตัด
            $table->integer('amount')->nullable(); 
            $table->decimal('price', 8, 2)->nullable(); 
            $table->decimal('deposit', 8, 2)->nullable(); 
            $table->text('note')->nullable(); 
            $table->decimal('damage_insurance', 8, 2)->nullable();  //ประกันค่าเสียหาย
            $table->decimal('total_damage_insurance', 8, 2)->nullable();  //ปรับจริงเท่าไหร่   
            $table->string('cause_for_insurance')->nullable(); //เหตุผลในการปรับ
            $table->boolean('cloth')->nullable(); // 1นำผ้ามาเอง 2.ร้านหาผ้าให้
            $table->string('status_detail')->nullable(); //สถานะของออเดอร์นี้
            $table->boolean('status_payment')->nullable(); // 1จ่ายมัดจำแล้ว 2.จ่ายเต็ม
            $table->decimal('late_fee', 8, 2)->nullable();  //ค่าปรับกรณีเลยกำหนดคืนชุด
            $table->decimal('total_other_price', 8, 2)->nullable(); //ผลรวมค่าปักดอกไม้เพิ่ม
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('jewelry_id')->references('id')->on('jewelries');
            $table->foreign('dress_id')->references('id')->on('dresses');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderdetails');
    }
};
