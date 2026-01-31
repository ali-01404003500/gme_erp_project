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
        Schema::create('salary_generates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->date('pay_date')->nullable();
            $table->string('year_month')->nullable();
            $table->decimal('basic', 20, 5)->default(0);
            $table->decimal('house_rent', 20, 5)->default(0);
            $table->decimal('medical', 20, 5)->default(0);
            $table->decimal('conveyance', 20, 5)->default(0);
            $table->decimal('others', 20, 5)->default(0);
            $table->decimal('ot_pay', 20, 5)->default(0);
            $table->decimal('double_time_pay', 20, 5)->default(0);
            $table->decimal('commission', 20, 5)->default(0);
            $table->decimal('bonus', 20, 5)->default(0);
            $table->decimal('leave_encashment', 20, 5)->default(0);
            $table->decimal('advance', 20, 5)->default(0);
            $table->decimal('loan', 20, 5)->default(0);
            $table->decimal('no_pay_leave', 20, 5)->default(0);
            $table->decimal('absence', 20, 5)->default(0);
            $table->decimal('tax', 20, 5)->default(0);

            $table->decimal('gross', 20, 5)->default(0);
            $table->decimal('total_other_earnings', 20, 5)->default(0);
            $table->decimal('total_earnings', 20, 5)->default(0);
            $table->decimal('total_deductions', 20, 5)->default(0);
            $table->decimal('total_tax', 20, 5)->default(0);
            $table->decimal('net_earning', 20, 5)->default(0);

            $table->string('status')->default('Pending');
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
        Schema::dropIfExists('salary_generates');
    }
};
