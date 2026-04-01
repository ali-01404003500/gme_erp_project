<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_signatories', function (Blueprint $table) {
            $table->string('role_name')->nullable()->after('employee_id'); // after kon column er por add korbe
        });
    }

    public function down(): void
    {
        Schema::table('salary_signatories', function (Blueprint $table) {
            $table->dropColumn('role_name');
        });
    }
};
