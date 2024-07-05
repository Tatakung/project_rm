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
        Schema::create('jewelries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_jewelry_id');
            $table->integer('jewelry_code')->nullable();
            $table->string('jewelry_title_name')->nullable();
            $table->string('jewelry_code_new')->nullable();
            $table->decimal('jewelry_price', 9, 2)->nullable();
            $table->decimal('jewelry_deposit', 9, 2)->nullable();
            $table->integer('jewelry_count')->nullable();
            $table->string('jewelry_status')->nullable();
            $table->text('jewelry_description')->nullable(); 
            $table->integer('jewelry_rental')->nullable();
            $table->timestamps();
            $table->softDeletes(); 
            $table->foreign('type_jewelry_id')->references('id')->on('typejewelries')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jewelries');
    }
};
