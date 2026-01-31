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
        Schema::create('sales_order_delivery_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_delivery_id')->constrained('sales_order_deliveries')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('product_catalogs')->cascadeOnDelete();
            $table->decimal('quantity', 20, 5)->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_delivery_details');
    }
};
