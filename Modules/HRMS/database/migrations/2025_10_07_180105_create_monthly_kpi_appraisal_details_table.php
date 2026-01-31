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
        Schema::create('monthly_kpi_appraisal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monthly_kpi_appraisal_id')->constrained('monthly_kpi_appraisals')->onDelete('cascade');
            $table->foreignId('responsibility_entry_id')->constrained('responsibility_entries')->onDelete('cascade');
            $table->decimal('target_days', 8, 2)->comment('T - Target Days');
            $table->decimal('actual_days', 8, 2)->nullable()->comment('A - Actual Days (manual entry)');
            $table->decimal('kpi_score', 5, 2)->default(0)->comment('K = (A/T) * 100');
            $table->decimal('weight', 5, 2)->comment('Weight from KPI assignment');
            $table->decimal('performance_score', 5, 2)->default(0)->comment('(K * Weight) / 100');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_kpi_appraisal_details');
    }
};
