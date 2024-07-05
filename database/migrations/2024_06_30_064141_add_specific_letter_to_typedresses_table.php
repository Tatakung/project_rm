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
        Schema::table('typedresses', function (Blueprint $table) {
            //
            $table->string('specific_letter')->nullable()->after('type_dress_name'); // เพิ่มคอลัมน์ใหม่
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typedresses', function (Blueprint $table) {
            //
            $table->dropColumn('specific_letter'); // ลบคอลัมน์ใหม่หากทำการ rollback
        });
    }
};
