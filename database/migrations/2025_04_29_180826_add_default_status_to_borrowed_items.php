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
            $table->string('request_status')->default('Pending')->change();
            $table->string('status')->default('Unreturned')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowed_items', function (Blueprint $table) {
            $table->string('request_status')->nullable()->change();
            $table->string('status')->nullable()->change();
        });
    }
};
