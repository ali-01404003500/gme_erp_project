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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->date('po_date')->nullable();
            $table->string('po_number')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('search_by_brand_id')->nullable()->constrained('brands');
            $table->string('transport_title')->nullable();
            $table->string('remarks')->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('shipping_terms')->nullable();
            $table->date('delivery_date')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('transport_cost')->nullable();
            $table->double('net_amount')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
