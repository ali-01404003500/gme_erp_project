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
        Schema::create('advance_cheque_entry_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advance_cheque_entry_id')->constrained('advance_cheque_entries');
            $table->foreignId('bank_id')->constrained('banks');
            $table->foreignId('branch_id')->constrained('bank_branches');
            $table->string('cheque_no');
            $table->date('cheque_date')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('document')->nullable();
            $table->tinyInteger('is_security_cheque')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_cheque_entry_details');
    }
};
