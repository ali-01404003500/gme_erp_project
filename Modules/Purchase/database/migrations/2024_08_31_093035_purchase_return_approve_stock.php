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
        Schema::create('purchase_return_approve_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('p_r_approve_detail_id')->constrained('purchase_return_approve_details')->onDelete('cascade');
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
        Schema::dropIfExists('purchase_return_approve_stocks');
    }
};
