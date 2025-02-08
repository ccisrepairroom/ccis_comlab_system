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
            $table->string('main_image')->nullable()->before('item')->index('supp_main_image');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplies_and_materials', function (Blueprint $table) {
            $table->dropIndex(['supp_main_image']);
            $table->dropColumn('main_image');

        });
    }
};
