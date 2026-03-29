<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_generates', function (Blueprint $table) {
            $table->renameColumn('absence', 'absent_deduction');
        });
    }

    public function down(): void
    {
        Schema::table('salary_generates', function (Blueprint $table) {
            $table->renameColumn('absent_deduction', 'absence');
        });
    }
};
