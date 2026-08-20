<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('sales_targets', function (Blueprint $table) {
            // Remove old foreign key
            $table->dropForeign(['employee_id']);

            // Add new foreign key to employees table
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('sales_targets', function (Blueprint $table) {
            // Remove employees foreign key
            $table->dropForeign(['employee_id']);

            // Restore users foreign key
            $table->foreign('employee_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
