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
        Schema::table('equipment_monitorings', function (Blueprint $table) {
            // Add the actions_taken column with a default value
            $table->enum('actions_taken', ['Resolved', 'Unresolved'])->default('Unresolved')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_monitorings', function (Blueprint $table) {
            // Drop the actions_taken column
            $table->dropColumn('actions_taken');
        });
    }
};
