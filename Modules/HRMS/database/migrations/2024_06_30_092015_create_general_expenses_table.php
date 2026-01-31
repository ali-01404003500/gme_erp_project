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
        Schema::create('general_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bills_and_allowance_id')->constrained('bills_and_allowances')->cascadeOnDelete();
            $table->string('expense_type');
            $table->text('expense_description');
            $table->decimal('amount', 10, 2);
            $table->decimal('settlement_amount', 10, 2);
            $table->string('receipts_invoices')->nullable();
            $table->string('supporting_documents')->nullable();
            $table->date('expense_date')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_expenses');
    }
};
