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
        //
        Schema::table('measurementorderdetails', function (Blueprint $table) {
            //
            $table->decimal('measurement_number_start', 9, 2)->after('measurement_name')->nullable();
            $table->decimal('measurement_number_old', 9, 2)->after('measurement_number_start')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
