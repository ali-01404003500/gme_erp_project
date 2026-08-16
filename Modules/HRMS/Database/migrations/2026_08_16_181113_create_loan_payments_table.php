<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_payments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('loan_id')
                ->constrained('loans')
                ->cascadeOnDelete();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            $table->unsignedInteger('installment_no');

            $table->date('due_date');

            $table->decimal('amount', 10, 2);

            $table->decimal('paid_amount', 10, 2)
                ->default(0);

            $table->date('payment_date')
                ->nullable();

            $table->string('payment_method')
                ->nullable();

            $table->string('reference_no')
                ->nullable();

            $table->text('remarks')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | pending
            | submitted
            | checked
            | approved
            | rejected
            | paid
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Checking
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('checked_by')
                ->nullable();

            $table->timestamp('checked_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Approval
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('approved_by')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Accounting Transaction
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('transaction_id')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('created_by')
                ->nullable();

            $table->unsignedBigInteger('updated_by')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'loan_id',
                'installment_no'
            ]);

            $table->index([
                'employee_id',
                'status'
            ]);

            $table->index('due_date');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};