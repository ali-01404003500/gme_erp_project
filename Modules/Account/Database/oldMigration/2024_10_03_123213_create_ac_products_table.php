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
        Schema::create('ac_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->string('name')->nullable();
            $table->string('product_type', 16, 2)->default(0)->nullable();
            $table->string('product_code')->default(0)->nullable();
            $table->string('product_code', 255)->default(0)->nullable()->change();
            $table->decimal('purchase_price', 16, 2)->default(0)->nullable();
            $table->decimal('selling_price', 16, 2)->default(0)->nullable();
            $table->decimal('opening_quantity', 16, 2)->default(0)->nullable();
            $table->decimal('current_stock', 16, 2)->default(0)->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ac_products');
    }
};
