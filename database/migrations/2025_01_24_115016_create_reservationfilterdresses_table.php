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
        Schema::create('reservationfilterdresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dress_id')->nullable();
            $table->unsignedBigInteger('shirtitems_id')->nullable();
            $table->unsignedBigInteger('skirtitems_id')->nullable();
            $table->date('start_date')->nullable() ; 
            $table->date('end_date')->nullable() ; 
            $table->string('status')->nullable() ; 
            $table->boolean('status_completed')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('dress_id')->references('id')->on('dresses')->onDelete('cascade');
            $table->foreign('shirtitems_id')->references('id')->on('shirtitems')->onDelete('cascade');
            $table->foreign('skirtitems_id')->references('id')->on('skirtitems')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservationfilterdresses');
    }
};
