<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salary_generation_policies', function (Blueprint $table) {
            $table->id();
            $table->string('calculation_type'); 
            $table->tinyInteger('fixed_days');
            $table->boolean('rounded_salary');
            $table->boolean('is_salary_end_date_different_from_month_end_date');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Insert a default row
        DB::table('salary_generation_policies')->insert([
            'calculation_type' => "actual_days", 
            'fixed_days' => 0,
            'rounded_salary' => false,
            'is_salary_end_date_different_from_month_end_date' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_generation_policies');
    }
};
