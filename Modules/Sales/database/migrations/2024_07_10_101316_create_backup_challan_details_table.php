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
        Schema::create('backup_challan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backup_challan_id')->constrained('backup_challans')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable()->constrained('product_catalogs')->onDelete('cascade');
            $table->unsignedBigInteger('quantity')->nullable();
            $table->unsignedBigInteger('price')->nullable();
            $table->unsignedBigInteger('amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_challan_details');
    }
};
