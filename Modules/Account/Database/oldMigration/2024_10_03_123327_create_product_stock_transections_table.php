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
        Schema::create('product_stock_transections', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('factory_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('source_type');
            $table->integer('source_id');
            $table->string('date'); //<----Source Date
            $table->decimal('quantity', 16, 2)->default(0);
            $table->decimal('price', 16, 2)->default(0);
            $table->decimal('amount', 16, 2)->virtualAs('quantity * price');
            $table->enum('stock_type', ['In', 'Out']);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('CASCADE');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_transections');
    }
};
