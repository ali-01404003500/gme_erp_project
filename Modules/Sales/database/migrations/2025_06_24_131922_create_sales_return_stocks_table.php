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
        Schema::create('sales_return_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_return_detail_id')->constrained('sales_return_details')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('product_catalogs')->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_return_stocks');
    }
};
