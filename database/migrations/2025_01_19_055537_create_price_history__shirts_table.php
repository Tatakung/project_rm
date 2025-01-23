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
        Schema::create('price_history__shirts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shirtitems_id');
            $table->decimal('old_price', 9, 2)->nullable();
            $table->decimal('new_price', 9, 2)->nullable();
            $table->timestamps();
            $table->softDeletes(); 
            $table->foreign('shirtitems_id')->references('id')->on('shirtitems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_history__shirts');
    }
};
