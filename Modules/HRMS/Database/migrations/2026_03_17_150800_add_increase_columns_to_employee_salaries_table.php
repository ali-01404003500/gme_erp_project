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
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->decimal('increase_basic', 10, 2)->default(0)->after('others');
            $table->decimal('increase_house_rent', 10, 2)->default(0)->after('increase_basic');
            $table->decimal('increase_conveyance', 10, 2)->default(0)->after('increase_house_rent');
            $table->decimal('increase_medical', 10, 2)->default(0)->after('increase_conveyance');
            $table->decimal('increase_entertainment', 10, 2)->default(0)->after('increase_medical');
            $table->decimal('increase_leave_fare', 10, 2)->default(0)->after('increase_entertainment');
            $table->decimal('increase_utility', 10, 2)->default(0)->after('increase_leave_fare');
            $table->decimal('increase_unkeep', 10, 2)->default(0)->after('increase_utility');
            $table->decimal('increase_others', 10, 2)->default(0)->after('increase_unkeep');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->dropColumn([
                'increase_basic',
                'increase_house_rent',
                'increase_conveyance',
                'increase_medical',
                'increase_entertainment',
                'increase_leave_fare',
                'increase_utility',
                'increase_unkeep',
                'increase_others'
            ]);
        });
    }
};
