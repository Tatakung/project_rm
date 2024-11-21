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
        Schema::create('reservationfilters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jewelry_id')->nullable();
            $table->unsignedBigInteger('jewelry_set_id')->nullable();
            $table->date('start_date')->nullable() ; 
            $table->date('end_date')->nullable() ; 
            $table->string('status')->nullable() ; 
            $table->boolean('status_completed')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('jewelry_id')->references('id')->on('jewelries');
            $table->foreign('jewelry_set_id')->references('id')->on('jewelrysets');
            $table->foreign('reservation_id')->references('id')->on('reservations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservationfilters');
    }
};
