<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales_salary_brackets', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('is_active')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->after('updated_by')->constrained('users')->nullOnDelete();
            $table->softDeletes(); // deleted_at কলাম যোগ করবে
        });
    }

    public function down()
    {
        Schema::table('sales_salary_brackets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('updated_by');
            $table->dropConstrainedForeignId('deleted_by');
            $table->dropSoftDeletes();
        });
    }
};
