<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE sales_targets MODIFY salary_basis ENUM('basic','gross','allexpenses') NOT NULL DEFAULT 'basic'");

        Schema::table('sales_targets', function (Blueprint $table) {
            $table->decimal('all_expenses_salary_at_assign', 12, 2)->nullable()->after('gross_salary_at_assign');
        });
    }

    public function down()
    {
        DB::statement("ALTER TABLE sales_targets MODIFY salary_basis ENUM('basic','gross') NOT NULL DEFAULT 'basic'");
        Schema::table('sales_targets', function (Blueprint $table) {
            $table->dropColumn('all_expenses_salary_at_assign');
        });
    }
};