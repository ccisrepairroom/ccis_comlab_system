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
        // Dropping the 'facility_img' column
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('facility_img');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Adding the 'facility_img' column back in case of rollback
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('facility_img')->nullable()->index('fac_facility_img');
        });
    }
};
