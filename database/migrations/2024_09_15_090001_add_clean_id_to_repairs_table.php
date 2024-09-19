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
            $table->unsignedBigInteger('clean_id')->nullable()->after('skirtitems_id');
            $table->foreign('clean_id')->references('id')->on('cleans')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repairs', function (Blueprint $table) {
            //
            $table->dropForeign(['clean_id']);
            $table->dropColumn('clean_id');

        });
    }
};
