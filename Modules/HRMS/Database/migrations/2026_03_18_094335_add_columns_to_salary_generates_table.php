<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('salary_generates', function (Blueprint $table) {
            $table->decimal('entertainment', 10, 2)->default(0)->after('conveyance');
            $table->decimal('leave_fare', 10, 2)->default(0)->after('entertainment');
            $table->decimal('utility', 10, 2)->default(0)->after('leave_fare');
            $table->decimal('unkeep', 10, 2)->default(0)->after('utility');

            $table->decimal('late_deduction', 10, 2)->default(0)->after('absence');
        });
    }

    public function down()
    {
        Schema::table('salary_generates', function (Blueprint $table) {
            $table->dropColumn(['entertainment', 'leave_fare', 'utility', 'unkeep', 'late_deduction']);
        });
    }
};
