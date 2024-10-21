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
        Schema::create('stock_units', function (Blueprint $table) {
            $table->id();
            $table->string('description')->index('su_description');
            $table->timestamps();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->index('su_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_units');
    }
};
