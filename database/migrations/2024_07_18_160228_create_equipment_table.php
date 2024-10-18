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
            $table->string('unit_no')->nullable();
            $table->string('description')->nullable();
            $table->string('specifications')->nullable()->nullable();
            $table->foreignId('facility_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('status')->nullable();
            $table->string('date_acquired')->nullable();
            $table->string('supplier')->nullable();
            $table->string('amount')->nullable();
            $table->string('estimated_life')->nullable();
            $table->string('item_no')->nullable();
            $table->string('property_no')->nullable();
            $table->string('control_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('no_of_stocks')->nullable();
            $table->string('restocking_point')->nullable();
            $table->unsignedBigInteger('stock_unit_id')->nullable();
            $table->foreign('stock_unit_id')->references('id')->on('stock_units')->onDelete('cascade');
            $table->string('person_liable')->nullable();
            $table->string('remarks')->nullable();
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
