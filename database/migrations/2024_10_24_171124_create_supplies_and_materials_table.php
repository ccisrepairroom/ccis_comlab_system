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
            $table->integer('stocking_point')->nullable()->index('supandman_stocking_point_index');
            $table->foreignId('stock_unit_id')->nullable()->constrained()->onDelete('cascade')->index('supandman_stock_unit_id_index');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->index('supandman_user_id_index'); 
            $table->foreignId('facility_id')->nullable()->constrained()->onDelete('cascade')->index('supandman_facility_id_index');
            $table->string('supplier')->nullable()->index('supandman_supplier_index');
            $table->string('date_acquired')->default(now()->format('M-d-y'))->nullable()->index('supandman_date_acquired_index');
            $table->text('remarks')->nullable()->index('supandman_remarks_index');
            //$table->string('item_img')->nullable()->index('supandman_item_img');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade')->index('supcart_category_id');
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
