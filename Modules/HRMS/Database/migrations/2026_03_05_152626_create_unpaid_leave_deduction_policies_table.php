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
        Schema::create('unpaid_leave_deduction_policies', function (Blueprint $table) {
            $table->id();
            $table->boolean('unpaid_consider');   
            $table->boolean('unpaid_deduct_gross'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unpaid_leave_deduction_policies');
    }
};
