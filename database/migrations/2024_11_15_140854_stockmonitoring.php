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
        Schema::create('stock_monitorings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplies_and_materials_id')->nullable()->index('stockmon_supplies_and_materials_id');
            $table->foreign('supplies_and_materials_id')->references('id')->on('supplies_and_materials')->onDelete('cascade');
            //$table->foreignId('supplies_and_materials_id')->nullable()->constrained('supplies_and_materials')->onDelete('cascade')->index('stockmon_supplies_and_materials_id');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade')->nullable()->index('stockmon_facility_id');
            $table->foreignId('monitored_by')->constrained('users')->onDelete('cascade')->nullable()->index('stockmon_monitored_by');
            $table->unsignedInteger('current_quantity')->nullable()->index('stockmon_current_quantity');
            $table->unsignedInteger('quantity_to_add')->nullable()->index('stockmon_quantity_to_add');
            $table->unsignedInteger('new_quantity')->nullable()->index('stockmon_new_quantity');
            $table->string('supplier')->nullable()->index('stockmon_supplier');
            $table->string('monitored_date')->default(now()->format('M-d-y'))->index('stockmon_monitored_date');          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_monitorings');
    }
};
