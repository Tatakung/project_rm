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
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dress_id')->nullable();
            $table->unsignedBigInteger('shirtitems_id')->nullable();
            $table->unsignedBigInteger('skirtitems_id')->nullable();
            $table->text('repair_description')->nullable(); 
            $table->string('repair_status')->nullable();
            
            $table->foreign('dress_id')->references('id')->on('dresses')->onDelete('cascade');
            $table->foreign('shirtitems_id')->references('id')->on('shirtitems')->onDelete('cascade');
            $table->foreign('skirtitems_id')->references('id')->on('skirtitems')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
