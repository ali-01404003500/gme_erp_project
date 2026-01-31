<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('condition_amount_collects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_verify_id')->constrained('shipment_verifies');
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_orders');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('courier_id')->nullable()->constrained('couriers');
            $table->decimal('invoice_amount', 20, 2)->default(0);
            $table->decimal('condition_amount', 20, 2)->default(0);
            $table->decimal('received_amount', 20, 2)->default(0);
            $table->string('status')->default('pending');
            $table->date('received_date')->nullable();
            $table->date('claim_date')->nullable();
            $table->string('remarks')->nullable();

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
        Schema::dropIfExists('condition_amount_collects');
    }
};
