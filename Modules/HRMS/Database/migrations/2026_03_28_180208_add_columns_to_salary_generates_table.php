<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_generates', function (Blueprint $table) {
            $table->integer('total_days')->nullable()->after('department_id');
            $table->integer('weekend')->nullable()->after('total_days');
            $table->integer('holidays')->nullable()->after('weekend');
            $table->integer('absent_days')->nullable()->after('holidays');
            $table->integer('late_days')->nullable()->after('absent_days');
            $table->integer('leave_days')->nullable()->after('late_days');
            $table->integer('working_days')->nullable()->after('leave_days');
            $table->decimal('approved_salary_ratio', 8, 2)->nullable()->after('working_days');
            $table->string('payment_method')->nullable()->after('status');
            $table->text('remarks')->nullable()->after('payment_method');

             // Approval status columns (boolean) and approval date (nullable)
            $table->boolean('checked_by_dept_head')->default(0)->after('updated_at');
            $table->dateTime('checked_date_dept_head')->nullable()->after('checked_by_dept_head');

            $table->boolean('checked_by_hr_head')->default(0)->after('checked_date_dept_head');
            $table->dateTime('checked_date_hr_head')->nullable()->after('checked_by_hr_head');

            $table->boolean('checked_by_admin_head')->default(0)->after('checked_date_hr_head');
            $table->dateTime('checked_date_admin_head')->nullable()->after('checked_by_admin_head');

            $table->boolean('checked_by_accounts_head')->default(0)->after('checked_date_admin_head');
            $table->dateTime('checked_date_accounts_head')->nullable()->after('checked_by_accounts_head');

            $table->boolean('checked_by_ceo')->default(0)->after('checked_date_accounts_head');
            $table->dateTime('checked_date_ceo')->nullable()->after('checked_by_ceo');

            $table->boolean('checked_by_md')->default(0)->after('checked_date_ceo');
            $table->dateTime('checked_date_md')->nullable()->after('checked_by_md');

            $table->boolean('checked_by_chairman')->default(0)->after('checked_date_md');
            $table->dateTime('checked_date_chairman')->nullable()->after('checked_by_chairman');

        });
    }

    public function down(): void
    {
        Schema::table('salary_generates', function (Blueprint $table) {
            $table->dropColumn([
                'total_days',
                'weekend',
                'holidays',
                'absent_days',
                'late_days',
                'leave_days',
                'working_days',
                'approved_salary_ratio',
                'payment_method',
                'remarks',
                'checked_by_dept_head',
                'checked_by_dept_head_date',
                'checked_by_hr_head',
                'checked_by_hr_head_date',
                'checked_by_admin_head',
                'checked_by_admin_head_date',
                'checked_by_accounts_head',
                'checked_by_accounts_head_date',
                'checked_by_ceo',
                'checked_by_ceo_date',
                'checked_by_md',
                'checked_by_md_date',
                'checked_by_chairman',
                'checked_by_chairman_date',
            ]);
        });
    }
};
