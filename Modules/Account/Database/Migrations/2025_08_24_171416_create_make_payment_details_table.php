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
        Schema::create('make_payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('make_payment_id')->constrained('make_payments');
            $table->string('pay_mode');
            $table->foreignId('bank_id')->nullable()->constrained('bank_accounts');
            $table->string('transaction_id')->nullable();
            $table->date('date');
            $table->decimal('amount', 20, 5);
            $table->string('attachments')->nullable();
            $table->boolean('verified')->default(false);
            $table->text('remark')->nullable();
            
            $table->timestamps();
            $table->nullableMorphs('paymentable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('make_payment_details');
    }
};
