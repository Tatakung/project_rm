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
        Schema::create('jewelryimages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jewelry_id')->nullable();
            $table->string('jewelry_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('jewelry_id')->references('id')->on('jewelries')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jewelryimages');
    }
};
