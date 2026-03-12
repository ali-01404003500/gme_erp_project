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
        Schema::create('product_transfer_receive_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_transfer_receive_id');
            $table->foreign('product_transfer_receive_id', 'ptrd_receive_id_foreign')->references('id')->on('product_transfer_receives')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'ptrd_product_id_foreign')->references('id')->on('product_catalogs')->onDelete('cascade');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('received_quantity', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_transfer_receive_details');
    }
};
