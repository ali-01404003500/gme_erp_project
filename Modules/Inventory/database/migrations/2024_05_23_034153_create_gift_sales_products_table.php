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
        Schema::create('gift_sales_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_detail_id')->constrained('offer_details')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('product_catalogs')->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_sales_products');
    }
};
