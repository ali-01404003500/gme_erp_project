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
        Schema::create('service_quotation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_quotation_id')->constrained('service_quotations');
            $table->foreignId('product_id')->constrained('product_catalogs');
            $table->decimal('quantity', 20, 5);
            $table->decimal('price', 20, 5);
            $table->decimal('unit_discount', 20, 5)->default(0);
            $table->decimal('total_discount', 20, 5)->default(0);
            $table->decimal('amount', 20, 5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_quotation_details');
    }
};
