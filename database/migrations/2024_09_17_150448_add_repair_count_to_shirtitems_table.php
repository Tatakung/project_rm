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
            $table->integer('repair_count')->nullable()->after('shirtitem_rental'); // เปลี่ยน 'column_name' เป็นคอลัมน์ที่คุณต้องการเพิ่มหลังจากนั้น

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shirtitems', function (Blueprint $table) {
            //
            $table->dropColumn('repair_count');
        });
    }
};
