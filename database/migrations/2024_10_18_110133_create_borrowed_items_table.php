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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('request_status');
            $table->string('request_form')->nullable();
           // $table->string('request_form')->nullable();
            $table->date('date')->nullable();
            $table->string('purpose')->nullable();
            $table->string('start_date_and_time_of_use')->nullable();
            $table->string('end_date_and_time_of_use')->nullable();
            $table->string('expected_return_date')->nullable();
            $table->string('received_by')->nullable();
            $table->string('college_department_office')->nullable();
            $table->string('borrowed_date')->nullable();
            $table->string('returned_date')->nullable();
            $table->string('status')->default('Unreturned');
            //$table->string('remarks')->nullable();
            $table->text('borrowed_by')->nullable();
            $table->text('remarks')->nullable();
            
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrows');
    }
};