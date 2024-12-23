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
        Schema::create('charge_jewelries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('additional_charge_id')->nullable();
            $table->unsignedBigInteger('jewelry_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('additional_charge_id')->references('id')->on('additional_changes');
            $table->foreign('jewelry_id')->references('id')->on('jewelrysets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_jewelries');
    }
};
