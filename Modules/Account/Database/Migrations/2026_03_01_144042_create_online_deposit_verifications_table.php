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
        Schema::create('online_deposit_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
             $table->foreignId('head_id')->nullable()->constrained('bank_accounts')->onDelete('cascade');
            $table->date('deposit_date')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('charge', 15, 2)->default(0.00);

            $table->json('document')->nullable(); 
            $table->text('remarks')->nullable();
            $table->string('status')->default('pending');
            $table->nullableMorphs('source'); 
 
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
        Schema::dropIfExists('online_deposit_verifications');
    }
};
