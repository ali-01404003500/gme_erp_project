<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Step 1: Add new column
        Schema::table('delay_policies', function (Blueprint $table) {
            $table->boolean('deduct_from_gross_salary')->nullable()->after('deduct_from_salary');
        });
 

        // Step 3: Drop old column
        Schema::table('delay_policies', function (Blueprint $table) {
            $table->dropColumn('deduct_from_salary');
        });
    }

    public function down()
    {
        // Rollback Step 1: Add old column back
        Schema::table('delay_policies', function (Blueprint $table) {
            $table->boolean('deduct_from_salary')->nullable()->after('deduct_from_gross_salary');
        });
 

        // Rollback Step 3: Drop new column
        Schema::table('delay_policies', function (Blueprint $table) {
            $table->dropColumn('deduct_from_gross_salary');
        });
    }

};
