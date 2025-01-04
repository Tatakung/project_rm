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
        Schema::create('dresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_dress_id');
            $table->integer('dress_code')->nullable();
            $table->string('dress_title_name')->nullable();
            $table->string('dress_color')->nullable();
            $table->decimal('dress_price', 9, 2)->nullable();
            $table->decimal('dress_deposit', 9, 2)->nullable();
            $table->integer('dress_count')->nullable();
            $table->string('dress_status')->nullable();
            $table->text('dress_description')->nullable(); 
            $table->integer('dress_rental')->nullable();
            $table->timestamps();
            $table->softDeletes(); 
            $table->foreign('type_dress_id')->references('id')->on('typedresses')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dresses');
    }
};
