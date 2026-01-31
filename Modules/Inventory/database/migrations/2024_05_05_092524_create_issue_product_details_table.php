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
        Schema::create('issue_product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_product_id')->constrained("issue_products")->onDelete('cascade');
            $table->foreignId('product_catalog_id')->constrained("products")->onDelete('cascade');
            $table->string('product_name')->nullable();
            $table->string('sku')->nullable();
            $table->foreignId('unit_type_id')->nullable()->constrained("units")->onDelete('cascade');
            $table->decimal('quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_product_details');
    }
};
