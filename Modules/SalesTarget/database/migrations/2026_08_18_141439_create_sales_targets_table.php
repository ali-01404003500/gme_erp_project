<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sales_target_slab_id')->nullable()->constrained('sales_target_slabs');
            $table->enum('salary_basis', ['basic', 'gross'])->default('basic');
            $table->decimal('salary_at_assign', 12, 2);
            $table->decimal('ta_da_at_assign', 12, 2);
            $table->decimal('gross_salary_at_assign', 12, 2)->nullable();
            $table->decimal('target_amount', 15, 2);
            $table->decimal('achieved_amount', 15, 2)->default(0);
            $table->decimal('achievement_percent', 6, 2)->default(0);
            $table->decimal('incentive_rate_applied', 6, 2)->nullable();
            $table->decimal('raw_incentive_amount', 15, 2)->default(0);
            $table->decimal('payout_percent_applied', 6, 2)->nullable();
            $table->decimal('final_incentive_amount', 15, 2)->default(0);
            $table->boolean('is_full_honor_override')->default(false);
            $table->foreignId('override_by')->nullable()->constrained('users');
            $table->timestamp('override_at')->nullable();
            $table->unsignedTinyInteger('period_month');
            $table->unsignedSmallInteger('period_year');
            $table->enum('status', ['pending', 'in_progress', 'achieved', 'not_achieved'])->default('pending');
            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->foreignId('locked_by')->nullable()->constrained('users');
            $table->boolean('is_synced_to_payroll')->default(false);
            $table->timestamps();

            $table->unique(['employee_id', 'period_month', 'period_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};
