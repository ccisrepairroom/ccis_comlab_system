<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            // Drop the existing index on 'remarks' first
            $table->dropIndex('fac_remarks');
        });

        // In a separate schema call, change the column type
        Schema::table('facilities', function (Blueprint $table) {
            $table->text('remarks')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            // Revert column back to string
            $table->string('remarks')->nullable()->change();
        });

        // In a separate schema call, re-add the index
        Schema::table('facilities', function (Blueprint $table) {
            $table->index('remarks', 'fac_remarks');
        });
    }
};
