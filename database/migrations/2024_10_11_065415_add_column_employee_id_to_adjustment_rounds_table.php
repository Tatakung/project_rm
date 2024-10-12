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
        Schema::table('adjustment_rounds', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('user_id')->after('order_detail_id')->nullable(); //FKพนักงาน
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adjustment_rounds', function (Blueprint $table) {
            //
        });
    }
};
