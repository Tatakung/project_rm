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
        Schema::table('shirtitems', function (Blueprint $table) {
            //
            $table->integer('shirt_adjustment')->nullable()->after('repair_count'); // เพิ่มคอลัมน์ dress_adjustment หลังจาก dress_rental
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shirtitems', function (Blueprint $table) {
            //
            $table->dropColumn('shirt_adjustment');
        });
    }
};
