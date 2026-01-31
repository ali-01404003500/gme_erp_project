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
        Schema::create('invoice_wise_collection_sales_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_wise_collection_id');
            $table->unsignedBigInteger('sales_order_id');
            $table->decimal('amount', 15, 2)->nullable(); // Amount paid for this specific sales order
            $table->timestamps();
            
            // Define foreign key constraints with shorter names
            $table->foreign('invoice_wise_collection_id', 'iwc_sales_collection_id_fk')
                  ->references('id')->on('invoice_wise_collections')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sales_order_id', 'iwc_sales_order_id_fk')
                  ->references('id')->on('sales_orders')->onDelete('cascade')->onUpdate('cascade');
            
            $table->unique(['invoice_wise_collection_id', 'sales_order_id'], 'iwc_sales_collection_order_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_wise_collection_sales_order');
    }
};