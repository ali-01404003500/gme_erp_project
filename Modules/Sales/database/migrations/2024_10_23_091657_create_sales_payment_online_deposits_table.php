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
        Schema::create('sales_payment_online_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_payment_detail_id')->constrained('sales_payment_details')->onDelete('cascade');
            $table->foreignId('online_deposit_bank_id')->constrained('banks')->onDelete('cascade');
            $table->foreignId('online_deposit_branch_id')->constrained('bank_branches')->onDelete('cascade');
            $table->string('online_deposit_no');
            $table->date('online_deposit_date');
            $table->string('online_deposit_remarks');
            $table->double('online_deposit_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_payment_online_deposits');
    }
};
