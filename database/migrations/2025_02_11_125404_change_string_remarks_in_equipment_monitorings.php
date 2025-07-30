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
        // First, check if there are any indexes on the remarks column and drop them
        $indexes = DB::select("SHOW INDEX FROM equipment_monitorings WHERE Column_name = 'remarks'");

        foreach ($indexes as $index) {
            if ($index->Key_name !== 'PRIMARY') {
                Schema::table('equipment_monitorings', function (Blueprint $table) use ($index) {
                    $table->dropIndex($index->Key_name);
                });
            }
        }

        // Now change the column type
        Schema::table('equipment_monitorings', function (Blueprint $table) {
            $table->text('remarks')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_monitorings', function (Blueprint $table) {
            $table->string('remarks')->nullable()->change();
        });
    }
};
