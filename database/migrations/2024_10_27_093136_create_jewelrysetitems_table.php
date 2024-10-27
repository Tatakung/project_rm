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
        Schema::create('jewelrysetitems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jewelry_set_id')->nullable();
            $table->unsignedBigInteger('jewelry_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('jewelry_set_id')->references('id')->on('jewelrysets')->onDelete('cascade');
            $table->foreign('jewelry_id')->references('id')->on('jewelries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jewelrysetitems');
    }
};
