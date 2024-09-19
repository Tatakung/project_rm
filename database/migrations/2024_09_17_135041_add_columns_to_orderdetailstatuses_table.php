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
        Schema::table('orderdetailstatuses', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('clean_id')->nullable()->after('order_detail_id');
            $table->unsignedBigInteger('repair_id')->nullable()->after('clean_id');
                        // เพิ่ม foreign key
            $table->foreign('clean_id')->references('id')->on('cleans')->onDelete('cascade');
            $table->foreign('repair_id')->references('id')->on('repairs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orderdetailstatuses', function (Blueprint $table) {
            //
            $table->dropForeign(['clean_id']);
            $table->dropForeign(['repair_id']);
            $table->dropColumn(['clean_id', 'repair_id']);
        });
    }
};
