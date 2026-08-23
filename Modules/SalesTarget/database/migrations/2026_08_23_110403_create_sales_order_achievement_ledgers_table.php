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
        Schema::create('sales_order_achievement_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees');
            $table->decimal('amount_applied', 15, 2)->default(0); // ei order theke oi employee-r target-e ekhon koto jog ache
            $table->unsignedTinyInteger('period_month');
            $table->unsignedSmallInteger('period_year');
            $table->timestamps();

            $table->unique(
                ['sales_order_id', 'employee_id'],
                'sales_order_emp_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_achievement_ledgers');
    }
};
