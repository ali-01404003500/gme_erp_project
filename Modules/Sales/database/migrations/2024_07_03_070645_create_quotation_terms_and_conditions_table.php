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
        Schema::create('quotation_terms_and_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations');
            $table->string('quotation_to')->nullable();
            $table->string('email')->nullable();
            $table->string('attn')->nullable();
            $table->string('attn_cell')->nullable();
            $table->text('payment')->nullable();
            $table->text('payment_method')->nullable();
            $table->text('tax_vat')->nullable();
            $table->text('installation')->nullable();
            $table->text('training')->nullable();
            $table->text('warranty')->nullable();
            $table->text('buyers_responsibility')->nullable();
            $table->text('validity')->nullable();
            $table->text('delivery_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_terms_and_conditions');
    }
};
