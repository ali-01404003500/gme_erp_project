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
        Schema::create('monthly_kpi_appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->unsignedBigInteger('kpi_template_assign_employee_id')->nullable();
            $table->string('assessment_month');
            $table->decimal('achieved_performance_score', 5, 2)->default(0);
            $table->string('performance_score_note')->nullable();
            $table->decimal('succession_management_score', 5, 2)->nullable();
            $table->string('succession_management_note')->nullable();
            $table->decimal('behavioral_performance_score', 5, 2)->nullable();
            $table->string('behavioral_performance_note')->nullable();
            $table->text('notes')->nullable();
            $table->string('status');
            
            $table->foreignId('approved_by')->nullable()->constrained('users');
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
        Schema::dropIfExists('monthly_kpi_appraisals');
    }
};
