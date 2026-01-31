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
        Schema::create('backup_challan_delivery_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backup_challan_delivery_id')->constrained('backup_challan_deliveries', 'id')->cascadeOnDelete()->name('bc_delivery_id_fk');
            $table->foreign('backup_challan_delivery_id', 'bc_delivery_id_fk')->references('id')->on('backup_challan_deliveries')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('product_catalogs')->cascadeOnDelete();
            $table->decimal('quantity', 20, 5)->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_challan_delivery_details');
    }
};
