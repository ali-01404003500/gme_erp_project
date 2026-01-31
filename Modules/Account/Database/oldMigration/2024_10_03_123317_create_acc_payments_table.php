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
        Schema::create('acc_payments', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('invoice_no')->nullable();
            $table->string('transaction_no')->nullable();
            $table->string('remarks')->nullable();
            $table->decimal('amount', 16, 2)->default(0);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('invoice_no')->references('invoice_no')->on('acc_purchases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acc_payments');
    }
};
