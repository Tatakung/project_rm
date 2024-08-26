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
        Schema::table('measurementorderdetails', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('dress_id')->after('order_detail_id')->nullable(); //FKorder_detail_id
            $table->unsignedBigInteger('item_shirt_id')->after('dress_id')->nullable(); //FKorder_detail_id
            $table->unsignedBigInteger('item_skirt_id')->after('item_shirt_id')->nullable(); //FKorder_detail_id
            $table->decimal('measurement_number_start', 9, 2)->first('measurement_name')->nullable();
            $table->decimal('measurement_number_old', 9, 2)->first('measurement_number_start')->nullable();
            $table->string('status_measurement')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table', function (Blueprint $table) {
            //
        });
    }
};
