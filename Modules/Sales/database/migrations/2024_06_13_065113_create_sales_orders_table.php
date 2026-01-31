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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('additional_phone')->nullable();
            $table->date('invoice_date');
            $table->decimal('total_amount', 20, 5);
            $table->decimal('discount', 20, 5);
            $table->decimal('commission', 20, 5);
            $table->decimal('total', 20, 5);
            $table->decimal('vat', 20, 5);
            $table->decimal('net_amount', 20, 5);
            $table->string('remarks')->nullable();
            $table->boolean('is_courier')->default(0);
            $table->string('status')->default('pending');
            $table->nullableMorphs('source');
            $table->boolean('is_shipment')->default(0);
            $table->date(column: 'delivery_date')->nullable();
            $table->string('sales_order_id')->nullable();

            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
