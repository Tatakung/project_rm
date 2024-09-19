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
        Schema::create('dressmeaadjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dressmea_id')->nullable();
            $table->unsignedBigInteger('order_detail_id')->nullable();
            $table->decimal('new_size', 9, 2)->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('dressmea_id')->references('id')->on('dressmeas')->onDelete('cascade');
            $table->foreign('order_detail_id')->references('id')->on('orderdetails')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dressmeaadjustments');
    }
};
