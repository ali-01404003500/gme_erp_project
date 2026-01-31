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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('pay_mode');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained('bank_branches')->cascadeOnDelete();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('attachments')->nullable();
            $table->boolean('verified')->default(false);
            $table->nullableMorphs('paymentable');
            $table->foreignId('e_m_i_entries_id')->nullable()->constrained('e_m_i_entries')->cascadeOnDelete();
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
        Schema::dropIfExists('payments');
    }
};
