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
        Schema::table('dresses', function (Blueprint $table) {
            //
            $table->integer('repair_count')->nullable()->after('dress_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dresses', function (Blueprint $table) {
            //
            $table->dropColumn('repair_count');
        });
    }
};
