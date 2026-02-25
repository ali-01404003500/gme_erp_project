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
        Schema::create('daily_credit_calls', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->date('call_date');
            $table->date('commitment_date')->nullable();
            $table->integer('before_reminder_date')->nullable();
            $table->decimal('commitment_amount', 15, 2)->nullable();
            $table->decimal('in_that_balance', 15, 2)->nullable(); 
            $table->text('remarks')->nullable();
            $table->string('status',20)->default('pending');
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
        Schema::dropIfExists('daily_credit_calls'); 
    }
    
};
