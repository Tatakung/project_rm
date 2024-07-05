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
        Schema::create('dressmeasurements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dress_id')->nullable();
            $table->string('measurement_dress_name')->nullable();
            $table->decimal('measurement_dress_number', 9, 2)->nullable();
            $table->string('measurement_dress_unit')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('dress_id')->references('id')->on('dresses')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dressmeasurements');
    }
};
