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
        Schema::create('shipment_verifies', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_id');
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('customer_address');
            $table->string('challan_no')->nullable();
            $table->foreignId('courier_id')->nullable()->constrained('couriers');
            $table->date('courier_date');
            $table->morphs('source');
            $table->decimal('service_charge', 10, 3)->nullable();
            $table->decimal('delivery_charge', 10, 3)->nullable();
            $table->decimal('other_charge', 10, 3)->nullable();
            $table->string('service_type')->nullable();
            $table->string('delivery_type')->nullable();
            $table->string('other_type')->nullable();
            $table->string('receipt_no')->nullable();
            $table->string('cartoon_no')->nullable();
            $table->date('receive_date')->nullable();

            $table->string('status')->default('pending');

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
        Schema::dropIfExists('shipment_verifies');
    }
};
