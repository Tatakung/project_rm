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
        Schema::create('afterreturnjews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservationfilter_id')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->decimal('price', 9, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('reservationfilter_id')->references('id')->on('reservationfilters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afterreturnjews');
    }
};
