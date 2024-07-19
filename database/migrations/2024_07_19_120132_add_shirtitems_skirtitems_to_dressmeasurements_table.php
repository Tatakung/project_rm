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
        Schema::table('dressmeasurements', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('shirtitems_id')->nullable()->after('dress_id');
            $table->unsignedBigInteger('skirtitems_id')->nullable()->after('shirtitems_id');
            $table->foreign('shirtitems_id')->references('id')->on('shirtitems')->onDelete('cascade');
            $table->foreign('skirtitems_id')->references('id')->on('skirtitems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dressmeasurements', function (Blueprint $table) {
            //
            $table->dropForeign(['shirtitems_id']);
            $table->dropForeign(['skirtitems_id']);
            $table->dropColumn('shirtitems_id');
            $table->dropColumn('skirtitems_id');
        });
    }
};
