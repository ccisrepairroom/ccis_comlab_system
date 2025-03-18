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
        Schema::create('request_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id')->nullable()->index('request_info_request_id');
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
            $table->string('name')->index('request_info_first_name')->nullable();
            $table->string('phone_number')->index('request_info_phone_number')->nullable();
            $table->string('college_department')->index('request_info_college_department')->nullable();
            $table->text('notes')->index('request_info_notes')->nullable();
            $table->text('purpose')->index('request_info_purpose')->nullable();
            $table->dateTime('start_date_and_time_of_use')->index('request_info_start_date_and_time_of_use')->nullable();
            $table->dateTime('end_date_and_time_of_use')->index('request_info_end_date_and_time_of_use')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_infos');
    }
};
