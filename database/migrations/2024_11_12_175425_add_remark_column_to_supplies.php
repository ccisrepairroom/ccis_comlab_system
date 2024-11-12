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
        Schema::table('supplies_and_materials', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('facility_id')->index('supandman_remarks_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplies_and_materials', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
};
