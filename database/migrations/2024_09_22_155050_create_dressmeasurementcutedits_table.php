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
        Schema::create('dressmeasurementcutedits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adjustment_id')->nullable();

            $table->unsignedBigInteger('order_detail_id')->nullable();
            
            $table->decimal('old_size', 9, 2)->nullable();
            $table->decimal('edit_new_size', 9, 2)->nullable();
            $table->integer('adjustment_number')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('adjustment_id')->references('id')->on('dressmeaadjustments')->onDelete('cascade');
            $table->foreign('order_detail_id')->references('id')->on('orderdetails')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dressmeasurementcutedits');
    }
};
