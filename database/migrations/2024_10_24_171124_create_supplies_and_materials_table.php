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
        Schema::create('supplies_and_materials', function (Blueprint $table) {
            $table->id();
            $table->string('item')->nullable()->index('supandman_item_index'); 
            $table->integer('quantity')->nullable()->index('supandman_quantity_index');
            $table->string('stocking_point')->nullable()->index('supandman_stocking_point_index');
            $table->foreignId('stock_unit_id')->nullable()->constrained()->onDelete('cascade')->index('supandman_stock_unit_id_index');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->index('supandman_user_id_index'); 
            $table->foreignId('facility_id')->nullable()->constrained()->onDelete('cascade')->index('supandman_facility_id_index');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies_and_materials');
    }
};
