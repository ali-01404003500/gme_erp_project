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
        Schema::create('sales_payment_card_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_payment_detail_id')->constrained('sales_payment_details')->onDelete('cascade');
            $table->unsignedBigInteger('card_collection_point');
            $table->string('card_payment_no');
            $table->date('card_payment_date');
            $table->string('card_payment_remarks');
            $table->double('card_payment_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_payment_card_payments');
    }
};
