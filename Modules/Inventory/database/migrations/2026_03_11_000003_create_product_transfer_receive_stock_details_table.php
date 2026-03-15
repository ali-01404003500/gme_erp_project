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
        Schema::create('product_transfer_receive_stock_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('details_id');
            $table->foreign('details_id', 'ptrsd_details_id_foreign')->references('id')->on('product_transfer_receive_details')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'ptrsd_product_id_foreign')->references('id')->on('product_catalogs')->onDelete('cascade');
            $table->string('lot_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->decimal('quantity', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_transfer_receive_stock_details');
    }
};
