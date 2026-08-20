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
        Schema::table('sales_salary_brackets', function (Blueprint $table) {
            $table->enum('payout_type', ['fixed', 'equal_to_achievement'])
                ->default('fixed')
                ->after('min_percent');
            $table->decimal('payout_percent', 6, 2)->nullable()->change(); // equal_to_achievement হলে এইটা লাগবে না
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_salary_brackets', function (Blueprint $table) {
            //
        });
    }
};
