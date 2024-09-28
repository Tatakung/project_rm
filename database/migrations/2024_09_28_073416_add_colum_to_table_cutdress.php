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
        Schema::table('dressmeasurementcutedits', function (Blueprint $table) {
            //
            $table->string('name')->after('order_detail_id')->nullable();  // เพิ่มคอลัมน์ใหม่
            $table->unsignedBigInteger('dress_id')->nullable();  // เพิ่มคอลัมน์ใหม่
            $table->unsignedBigInteger('shirtitems_id')->nullable();  // เพิ่มคอลัมน์ใหม่
            $table->unsignedBigInteger('skirtitems_id')->nullable();  // เพิ่มคอลัมน์ใหม่

            // เพิ่ม foreign keys
            $table->foreign('dress_id')->references('id')->on('dresses')->onDelete('cascade');
            $table->foreign('shirtitems_id')->references('id')->on('shirtitems')->onDelete('cascade');
            $table->foreign('skirtitems_id')->references('id')->on('skirtitems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dressmeasurementcutedits', function (Blueprint $table) {
            //
            // ลบ foreign keys ก่อน
            $table->dropForeign(['dress_id']);
            $table->dropForeign(['shirtitems_id']);
            $table->dropForeign(['skirtitems_id']);

            // ลบคอลัมน์
            $table->dropColumn(['name', 'dress_id', 'shirtitems_id', 'skirtitems_id']);
        });
    }
};
