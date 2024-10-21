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
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade')->index('stockmon_equipment_id');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade')->index('stockmon_facility_id');
            $table->foreignId('monitored_by')->constrained('users')->onDelete('cascade')->index('stockmon_monitored_by');
            $table->unsignedInteger('available_stock')->nullable()->index('stockmon_available_stock');
            $table->string('action_type')->index('stockmon_action_type'); // 'add' or 'deduct'
            $table->unsignedInteger('stock_quantity')->index('stockmon_stock_quantity'); // Amount added or deducted
            $table->unsignedInteger('new_stock')->index('stockmon_new_stock'); // Resulting stock after action
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
