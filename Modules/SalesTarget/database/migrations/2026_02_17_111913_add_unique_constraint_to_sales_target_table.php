<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_target', function (Blueprint $table) {

            $table->unique(['employee_id', 'year'], 'emp_year_unique');
        });
    }

    public function down(): void
    {
        Schema::table('sales_target', function (Blueprint $table) {
            $table->dropUnique('emp_year_unique');
        });
    }
};
