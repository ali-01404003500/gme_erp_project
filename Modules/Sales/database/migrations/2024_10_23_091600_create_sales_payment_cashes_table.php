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
        Schema::create('sales_payment_cashes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_payment_detail_id')->constrained('sales_payment_details')->onDelete('cascade');
            $table->double(column: 'cash_payment_amount');
            $table->date('cash_payment_date');
            $table->string('cash_payment_remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_payment_cashes');
    }
};
