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
        Schema::table('shirtitems', function (Blueprint $table) {
            //
            $table->decimal('shirt_damage_insurance', 9, 2)->nullable()->after('shirtitem_rental');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shirtitems', function (Blueprint $table) {
            //
            $table->dropColumn('shirt_damage_insurance');

        });
    }
};
