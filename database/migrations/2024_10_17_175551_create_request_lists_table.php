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
        Schema::create('request_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->index('reqlist_user_id');
            $table->foreignId('equipment_id')->nullable()->constrained()->onDelete('cascade')->index('reqlist_equipment_id');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade')->index('reqlist_facility_id');
            $table->date('date')->nullable()->index('reqlist_date');
            $table->string('purpose')->nullable()->index('reqlist_purpose');
            $table->string('start_date_and_time_of_use')->nullable()->index('reqlist_start_time');
            $table->string('end_date_and_time_of_use')->nullable()->index('reqlist_end_time');
            $table->string('expected_return_date')->nullable()->index('reqlist_return_date');
            $table->string('college_department_office')->nullable()->index('reqlist_department');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_lists');
    }
};
