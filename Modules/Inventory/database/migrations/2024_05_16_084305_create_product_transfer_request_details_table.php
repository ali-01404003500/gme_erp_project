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
        Schema::create('product_transfer_request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_transfer_request_id')->constrained('product_transfer_requests', 'id', 'product_transfer_request_id_foreign')->onDelete('cascade');
            $table->foreignId('product_catalog_id')->constrained('product_catalogs')->onDelete('cascade');
            $table->decimal('quantity', 12, 4)->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_transfer_request_details');
    }
};
