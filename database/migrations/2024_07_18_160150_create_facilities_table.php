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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->index('fac_name');
            $table->string('connection_type')->nullable()->index('fac_connection_type');
            $table->string('facility_type')->nullable()->index('fac_facility_type');
            $table->string('cooling_tools')->nullable()->index('fac_cooling_tools');
            $table->string('floor_level')->nullable()->index('fac_floor_level');
            $table->string('building')->default('HIRAYA')->index('fac_building');
            $table->string('remarks')->nullable()->index('fac_remarks');
            $table->string('facility_img')->nullable()->index('fac_facility_img');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->index('fac_user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
