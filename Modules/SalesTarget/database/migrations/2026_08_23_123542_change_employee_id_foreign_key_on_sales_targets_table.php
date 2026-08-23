<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales_targets', function (Blueprint $table) {
            // Remove existing FK: sales_targets.employee_id -> users.id
            $table->dropForeign(['employee_id']);

            // Add new FK: sales_targets.employee_id -> employees.id
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('sales_targets', function (Blueprint $table) {
            // Remove employees FK
            $table->dropForeign(['employee_id']);

            // Restore users FK
            $table->foreign('employee_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
