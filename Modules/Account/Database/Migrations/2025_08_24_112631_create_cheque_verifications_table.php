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
        Schema::create('cheque_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_id')->constrained('banks');
            $table->foreignId('branch_id')->constrained('bank_branches');
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->date('deposit_date')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('charge', 15, 2)->default(0.00);

            $table->json('document')->nullable();
            $table->foreignId('head_id')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->text('remarks')->nullable();
            $table->string('status')->default('pending');
            $table->nullableMorphs('source');

            $table->foreignId('deposited_by')->nullable()->constrained('users');
            $table->foreignId('encashed_by')->nullable()->constrained('users');
            $table->foreignId('encash_verified_by')->nullable()->constrained('users');
            $table->foreignId('dishonored_by')->nullable()->constrained('users');

            
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
        Schema::dropIfExists('cheque_verifications');
    }
};
