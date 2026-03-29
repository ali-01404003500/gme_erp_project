<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_generates', function (Blueprint $table) {
            $table->integer('checked_by_dept_head')->change();
            $table->integer('checked_by_hr_head')->change();
            $table->integer('checked_by_admin_head')->change();
            $table->integer('checked_by_accounts_head')->change();
            $table->integer('checked_by_ceo')->change();
            $table->integer('checked_by_md')->change();
            $table->integer('checked_by_chairman')->change();
        });
    }

    public function down(): void
    {
        Schema::table('salary_generates', function (Blueprint $table) {
            // যদি আগের type boolean/tinyint ছিল
            $table->boolean('checked_by_dept_head')->change();
            $table->boolean('checked_by_hr_head')->change();
            $table->boolean('checked_by_admin_head')->change();
            $table->boolean('checked_by_accounts_head')->change();
            $table->boolean('checked_by_ceo')->change();
            $table->boolean('checked_by_md')->change();
            $table->boolean('checked_by_chairman')->change();
        });
    }
};
