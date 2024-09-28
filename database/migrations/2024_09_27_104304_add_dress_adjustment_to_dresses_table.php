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
        Schema::table('dresses', function (Blueprint $table) {
            //
            $table->integer('dress_adjustment')->nullable()->after('dress_rental'); // เพิ่มคอลัมน์ dress_adjustment หลังจาก dress_rental

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dresses', function (Blueprint $table) {
            //
            $table->dropColumn('dress_adjustment');

        });
    }
};
