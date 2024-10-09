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
        Schema::create('additional_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_detail_id')->nullable();
            $table->integer('charge_type')->nullable();
            $table->decimal('amount', 9, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('order_detail_id')->references('id')->on('orderdetails')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_changes');
    }
};
