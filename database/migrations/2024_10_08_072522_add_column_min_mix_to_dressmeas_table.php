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
        Schema::table('dressmeas', function (Blueprint $table) {
            //
            $table->decimal('initial_min', 9, 2)->after('initial_mea')->nullable();
            $table->decimal('initial_max', 9, 2)->after('initial_min')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dressmeas', function (Blueprint $table) {
            //
        });
    }
};
