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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id('achievement_id'); 
            $table->foreignId('employee_id')->constrained('users'); 
            $table->date('invoice_date');
            $table->string('invoice_number'); 
            $table->string('invoice_month'); 
            $table->decimal('invoice_amount', 15, 2); 
            $table->decimal('achievement_amount', 15, 2); 
            $table->string('invoice_type'); 
            $table->decimal('invoice_collection_amount', 15, 2)->default(0); 
            $table->decimal('invoice_due_amount', 15, 2)->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
