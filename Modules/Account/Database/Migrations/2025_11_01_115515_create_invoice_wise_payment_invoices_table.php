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
        Schema::create('invoice_wise_payment_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_wise_payment_id')->constrained()->onDelete('cascade');
            $table->morphs('invoice'); // requisition_id or office_purchase_id
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_wise_payment_invoices');
    }
};
