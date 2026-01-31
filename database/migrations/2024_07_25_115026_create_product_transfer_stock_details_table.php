<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * 
     * @return void
     */
    public function up(): void
    {
        Schema::create('product_transfer_stock_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('details_id');
            $table->unsignedBigInteger('product_id');
            $table->string('lot_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_transfer_stock_details');
    }
};
