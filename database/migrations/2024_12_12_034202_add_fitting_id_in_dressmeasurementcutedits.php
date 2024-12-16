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
            $table->unsignedBigInteger('fitting_id')->after('order_detail_id')->nullable();
            $table->foreign('fitting_id')->references('id')->on('fittings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dressmeasurementcutedits', function (Blueprint $table) {
            //
        });
    }
};
