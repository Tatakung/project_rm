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
        Schema::table('repairs', function (Blueprint $table) {
            //
            $table->integer('repair_type')->nullable()->after('repair_status'); // เพิ่มคอลัมน์ repair_type

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repairs', function (Blueprint $table) {
            //
            $table->dropColumn('repair_type'); // ลบคอลัมน์ repair_type เมื่อ rollback migration

        });
    }
};
