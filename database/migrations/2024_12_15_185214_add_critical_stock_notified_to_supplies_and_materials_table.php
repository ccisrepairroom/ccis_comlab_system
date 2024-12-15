<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('supplies_and_materials', function (Blueprint $table) {
            $table->boolean('critical_stock_notified')->default(false)->after('stocking_point');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('supplies_and_materials', function (Blueprint $table) {
            $table->dropColumn('critical_stock_notified');
        });
    }
};
