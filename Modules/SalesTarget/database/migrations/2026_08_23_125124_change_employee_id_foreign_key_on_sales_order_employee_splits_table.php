<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales_order_employee_splits', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('sales_order_employee_splits', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);

            $table->foreign('employee_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
