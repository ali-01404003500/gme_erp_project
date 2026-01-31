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
        Schema::create('free_sales_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('free_sales_invoice_id')->constrained('free_sales_invoices')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('product_catalogs')->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('free_sales_invoice_details');
    }
};
