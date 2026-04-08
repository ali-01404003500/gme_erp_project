<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    DB::statement('ALTER TABLE salary_generates CHANGE absence absent_deduction DECIMAL(8,2)'); 
    // Match the DECIMAL(8,2) to whatever your 'absence' column currently is.
}

    public function down(): void
    {
        Schema::table('salary_generates', function (Blueprint $table) {
            $table->renameColumn('absent_deduction', 'absence');
        });
    }
};
