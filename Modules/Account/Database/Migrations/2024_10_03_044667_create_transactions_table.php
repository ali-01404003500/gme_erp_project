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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('transactionable');
            $table->string('invoice_no')->nullable();
            $table->string('redirect_path')->nullable();
            $table->string('balance_type')->nullable();
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->decimal('amount', 15);
            $table->string('description')->nullable();
            $table->string('transaction_item_type')->nullable();
            $table->string('batch_id')->nullable();
            $table->decimal('debit_amount', 16, 4)->nullable();
            $table->decimal('credit_amount', 16, 4)->nullable();
            
            $table->timestamps();

            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
