<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('salary_verifications', function (Blueprint $table) {
            $table->string('role_name')->nullable()->after('approver_id'); 
            // 'approver_id' er por e column add hobe, nullable rakhlam jate existing data break na hoy
        });
    }

    public function down(): void
    {
        Schema::table('salary_verifications', function (Blueprint $table) {
            $table->dropColumn('role_name');
        });
    }
};
