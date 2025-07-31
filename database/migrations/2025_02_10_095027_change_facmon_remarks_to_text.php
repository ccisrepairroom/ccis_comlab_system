<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's check and drop any existing indexes on the remarks column
        $indexes = DB::select("SHOW INDEX FROM facility_monitorings WHERE Column_name = 'remarks'");

        foreach ($indexes as $index) {
            Schema::table('facility_monitorings', function (Blueprint $table) use ($index) {
                $table->dropIndex($index->Key_name);
            });
        }

        // Now change the column type
        Schema::table('facility_monitorings', function (Blueprint $table) {
            $table->text('remarks')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facility_monitorings', function (Blueprint $table) {
            $table->string('remarks')->nullable()->change();
        });

        // Re-add index if it was there originally (adjust index name as needed)
        // Schema::table('facility_monitorings', function (Blueprint $table) {
        //     $table->index('remarks', 'some_index_name');
        // });
    }
};
