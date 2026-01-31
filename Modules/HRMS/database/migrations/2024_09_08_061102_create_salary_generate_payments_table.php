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
        Schema::create('salary_generate_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_generate_id')->constrained('salary_generates')->onDelete('cascade');
            $table->decimal('amount', 20, 5);
            $table->date('pay_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_generate_payments');
    }
};
