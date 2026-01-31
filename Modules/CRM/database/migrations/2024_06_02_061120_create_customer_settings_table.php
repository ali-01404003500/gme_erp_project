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
        Schema::create('customer_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->unsignedBigInteger('customer_rating');
            $table->tinyInteger('customer_status');
            $table->double('credit_limit', 12, 4);
            $table->double('additional_credit_limit', 12, 4);
            $table->double('opening_balance', 12, 4);
            $table->tinyInteger('is_condition_bill');
            $table->tinyInteger('vat_status');
            $table->tinyInteger('is_document_return');
            $table->tinyInteger('service_applicable');
            $table->unsignedBigInteger('minimum_condition_bill');
            $table->tinyInteger('discount_type')->default(0);
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
        Schema::dropIfExists('customer_settings');
    }
};
