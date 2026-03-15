<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('effective_from');
            $table->string('working_hours')->nullable();
            $table->time('in_time')->nullable();
            $table->string('delay_buffer')->default('00:00');
            $table->string('ex_delay_buffer')->default('00:00');
            $table->time('early_out_time')->nullable();
            $table->integer('break_time')->default(0);
            
            // Checkboxes/Settings
            $table->boolean('ignore_ot_deduction')->default(false);
            $table->boolean('exclude_from_reports')->default(false);
            $table->boolean('discard_weekend')->default(false);
            
            // JSON column for the 7-day schedule table
            $table->json('day_wise_settings')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_policies');
    }
};