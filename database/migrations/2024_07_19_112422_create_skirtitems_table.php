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
        Schema::create('skirtitems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dress_id');
            $table->decimal('skirtitem_price', 9, 2)->nullable();
            $table->decimal('skirtitem_deposit', 9, 2)->nullable();
            $table->string('skirtitem_status')->nullable();
            $table->integer('skirtitem_rental')->nullable();
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
        Schema::dropIfExists('skirtitems');
    }
};
