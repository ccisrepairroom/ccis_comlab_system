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
            Schema::create('request_items', function (Blueprint $table) {
                $table->id();
                // Ensure that the foreign key fields match the types of the referenced primary key
                $table->unsignedBigInteger('request_id')->nullable()->index('request_items_request_id');
                $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
    
                $table->unsignedBigInteger('equipment_id')->nullable()->index('request_items_equipment_id');
                $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');

                $table->unsignedBigInteger('facility_id')->nullable()->index('request_items_facility_id');
                $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');

                $table->unsignedBigInteger('supplies_and_materials_id')->nullable()->index('request_items_supplies_and_materials_id');
                $table->foreign('supplies_and_materials_id')->references('id')->on('supplies_and_materials')->onDelete('cascade');
                
                $table->integer('quantity')->default(1)->nullable()->index('request_items_quantity');
             
                
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_items');
    }
};
