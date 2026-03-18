<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->boolean('is_consolidate')->after('salary_setup_id')->default(0);
            $table->decimal('entertainment', 10, 2)->nullable()->after('conveyance')->default(0);
            $table->decimal('leave_fare', 10, 2)->nullable()->after('entertainment')->default(0);
            $table->decimal('utility', 10, 2)->nullable()->after('leave_fare')->default(0);
            $table->decimal('unkeep', 10, 2)->nullable()->after('utility')->default(0);
            $table->string('payment_type', 10)->nullable()->after('tax')->default('bank');
        });
    }

    public function down()
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->dropColumn([
                'is_consolidate',
                'entertainment',
                'leave_fare',
                'utility',
                'unkeep',
                'payment_type'
            ]);
        });
    }
};
