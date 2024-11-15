



<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('supplies_cart', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->index('supcart_user_id_index')->nullable(); // Foreign key for users
            $table->string('requested_by')->nullable()->index('supcart_requested_by_index');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade')->index('supcart_facility_id_index')->nullable();
            //$table->string('office_entity')->index('supcart_office_entity')->nullable(); // Text input for office/entity
            //$table->string('requested_by')->index('supcart_requested_by')->nullable();
            $table->foreignId('supplies_and_materials_id')->constrained()->onDelete('cascade')->index('supcart_supplies_and_materials_id')->nullable(); // Foreign key for items
            $table->integer('available_quantity')->index('supcart_available_quantity')->nullable(); // Previous quantity of the item
            //$table->enum('stock_action', ['deducted', 'added'])->index('supcart_stock_action')->nullable(); // Stock action (deducted/added)
            $table->integer('quantity_requested')->index('supcart_quantity_requested')->nullable(); // Quantity added/deducted
            //$table->string('deducted_added_by')->index('supcart_deducted_added_by')->nullable(); // User who deducted/added the stock
            $table->string('date_requested')->default(now()->format('M-d-y'))->index('supcart_date_requested')->nullable(); // Date of the action
            $table->foreignId('stock_unit_id')->nullable()->constrained()->onDelete('cascade')->index('supcart_stock_unit_id_index');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade')->index('supcart_category_id_index');
            $table->text('supplier')->nullable()->index('supcart_supplier_index');
            $table->text('remarks')->nullable()->index('supcart_remarks_index');
            $table->timestamps(); // Created at and updated at timestamps
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies_cart');
    }
};
