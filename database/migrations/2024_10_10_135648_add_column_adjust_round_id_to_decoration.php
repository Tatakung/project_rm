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
        Schema::table('decorations', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('adjustment_round_id')->after('order_detail_id')->nullable();
            $table->foreign('adjustment_round_id')->references('id')->on('adjustment_rounds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('decorations', function (Blueprint $table) {
            //
        });
    }
};
