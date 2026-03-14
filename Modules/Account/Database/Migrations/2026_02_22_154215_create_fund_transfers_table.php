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
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_type');
            $table->date('transfer_date'); 
            $table->foreignId('transfer_from'); 
            $table->foreignId('transfer_to');
            $table->date('cheque_date')->nullable();
            $table->string('cheque_no')->nullable(); 
            $table->double('amount'); 
            $table->text('remarks');
            $table->json('attachments'); 
            $table->foreignId('approve_by')->nullable()->constrained('users')->nullOnDelete(); 
            $table->foreignId('approve_date')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_transfers');
    }
};
