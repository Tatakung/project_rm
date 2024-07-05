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
        Schema::create('typejewelries', function (Blueprint $table) {
            $table->id();
            $table->string('type_jewelry_name')->nullable();
            $table->string('specific_letter')->nullable(); // เพิ่มคอลัมน์ใหม่
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typejewelries');
    }
};
