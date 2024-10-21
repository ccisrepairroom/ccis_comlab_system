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
        Schema::create('borrowed_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->index('borroweditem_user_id');
            $table->foreignId('equipment_id')->nullable()->constrained()->onDelete('cascade')->index('borroweditem_equipment_id');
            $table->foreignId('facility_id')->nullable()->constrained()->onDelete('cascade')->index('borroweditem_facility_id');
            $table->string('request_status')->index('borroweditem_request_status');
            $table->string('request_form')->nullable()->index('borroweditem_request_form');
            $table->date('date')->nullable()->index('borroweditem_date');
            $table->string('purpose')->nullable()->index('borroweditem_purpose');
            $table->string('start_date_and_time_of_use')->nullable()->index('borroweditem_start_time');
            $table->string('end_date_and_time_of_use')->nullable()->index('borroweditem_end_time');
            $table->string('expected_return_date')->nullable()->index('borroweditem_return_date');
            $table->string('received_by')->nullable()->index('borroweditem_received_by');
            $table->string('college_department_office')->nullable()->index('borroweditem_department');
            $table->string('borrowed_date')->nullable()->index('borroweditem_borrowed_date');
            $table->string('returned_date')->nullable()->index('borroweditem_returned_date');
            $table->string('status')->default('Unreturned')->index('borroweditem_status');
            $table->text('borrowed_by')->nullable()->index('borroweditem_borrowed_by');
            $table->text('remarks')->nullable()->index('borroweditem_remarks');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowed_items');
    }
};
