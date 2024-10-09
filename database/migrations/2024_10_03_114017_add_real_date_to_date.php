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
        Schema::table('dates', function (Blueprint $table) {
            //
            $table->date('actua_pickup_date')->after('return_date')->nullable();
            $table->date('actua_return_date')->after('actua_pickup_date')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dates', function (Blueprint $table) {
            //
        });
    }
};
