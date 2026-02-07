<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. Achievement %	Variable Pay Eligibility	Salary Rule

     */
    public function up(): void
    {
        Schema::create('achievement_based_salary_policies', function (Blueprint $table) {
            $table->id();
            $table->integer('achievement_percentage_start');
            $table->integer('achievement_percentage_end');
            $table->string('variable_pay_eligibility');
            $table->string('salary_rule_type');
            $table->integer('salary_rule_value');
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
        Schema::dropIfExists('achievement_based_salary_policies');
    }
};
