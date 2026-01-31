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
        Schema::create('backup_challan_delivery_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('b_c_d_p_details_id')->references('id')->on('backup_challan_delivery_details')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_catalog_id')->references('id')->on('product_catalogs')->onDelete('cascade')->onUpdate('cascade');
            $table->string('serial_no')->nullable();
            $table->unsignedDouble('quantity')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('backup_challan_type')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_challan_delivery_stocks');
    }
};
