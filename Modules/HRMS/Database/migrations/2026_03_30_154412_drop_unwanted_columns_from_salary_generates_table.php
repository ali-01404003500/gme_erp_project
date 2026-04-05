<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_generates', function (Blueprint $table) {
            $table->dropColumn([
                'checked_by_dept_head',
                'checked_date_dept_head',
                'checked_by_hr_head',
                'checked_date_hr_head',
                'checked_by_admin_head',
                'checked_date_admin_head',
                'checked_by_accounts_head',
                'checked_date_accounts_head',
                'checked_by_ceo',
                'checked_date_ceo',
                'checked_by_md',
                'checked_date_md',
                'checked_by_chairman',
                'checked_date_chairman',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('salary_generates', function (Blueprint $table) { 
            $table->unsignedBigInteger('checked_by_dept_head')->nullable();
            $table->timestamp('checked_date_dept_head')->nullable();
            $table->unsignedBigInteger('checked_by_hr_head')->nullable();
            $table->timestamp('checked_date_hr_head')->nullable();
            $table->unsignedBigInteger('checked_by_admin_head')->nullable();
            $table->timestamp('checked_date_admin_head')->nullable();
            $table->unsignedBigInteger('checked_by_accounts_head')->nullable();
            $table->timestamp('checked_date_accounts_head')->nullable();
            $table->unsignedBigInteger('checked_by_ceo')->nullable();
            $table->timestamp('checked_date_ceo')->nullable();
            $table->unsignedBigInteger('checked_by_md')->nullable();
            $table->timestamp('checked_date_md')->nullable();
            $table->unsignedBigInteger('checked_by_chairman')->nullable();
            $table->timestamp('checked_date_chairman')->nullable();
        });
    }
};
