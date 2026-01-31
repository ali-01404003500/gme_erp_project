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
        Schema::create('broker_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_commission_id')->constrained('sales_commissions')->cascadeOnDelete();
            $table->foreignId('broker_payment_bank_id')->constrained('broker_banks')->cascadeOnDelete();
            $table->decimal('payment_amount', 20, 2);
            $table->text('remarks')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('rejected_by')->nullable()->constrained('users');

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
        Schema::dropIfExists('broker_payments');
    }
};
