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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->nullable()->index('po_number');
            $table->string('unit_no')->nullable()->index('equip_unit_no');
            $table->string('brand_name')->nullable()->index('equip_brand_name');
            $table->text('description')->nullable()->index('equip_description');
            $table->foreignId('facility_id')->nullable()->constrained()->onDelete('cascade')->index('equip_facility_id');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade')->index('equip_category_id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->index('equip_user_id');
            $table->string('status')->nullable()->index('equip_status');
            $table->string('date_acquired')->nullable()->index('equip_date_acquired');
            $table->string('supplier')->nullable()->index('equip_supplier');
            $table->string('amount')->nullable()->index('equip_amount');
            $table->string('estimated_life')->nullable()->index('equip_estimated_life');
            $table->string('item_no')->nullable()->index('equip_item_no');
            $table->string('property_no')->nullable()->index('equip_property_no');
            $table->string('control_no')->nullable()->index('equip_control_no');
            $table->string('serial_no')->nullable()->index('equip_serial_no');
            //$table->string('no_of_stocks')->nullable()->index('equip_no_of_stocks');
            //$table->string('restocking_point')->nullable()->index('equip_restocking_point');
            //$table->unsignedBigInteger('stock_unit_id')->nullable()->index('equip_stock_unit_id');
            //$table->foreign('stock_unit_id')->references('id')->on('stock_units')->onDelete('cascade');
            $table->string('person_liable')->nullable()->index('equip_person_liable');
            $table->text('remarks')->nullable()->index('equip_remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
