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
        Schema::create('decorations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_detail_id')->nullable(); //FKorder_detail_if
            $table->string('decoration_description')->nullable();
            $table->decimal('decoration_price', 8, 2)->nullable(); 
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('order_detail_id')->references('id')->on('orderdetails')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decorations');
    }
};
