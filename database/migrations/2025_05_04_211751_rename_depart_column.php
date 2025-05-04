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
        Schema::table('borrowed_items', function (Blueprint $table) {
            $table->renameColumn('college_department_office', 'college_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowed_items', function (Blueprint $table) {
            $table->renameColumn('college_department', 'college_department_office');
        });
    }
};
