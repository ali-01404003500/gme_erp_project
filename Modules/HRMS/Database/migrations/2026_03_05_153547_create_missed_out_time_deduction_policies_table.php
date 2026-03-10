<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('missed_out_time_deduction_policies', function (Blueprint $table) {
            $table->id();
            $table->boolean('consider_missed_out');
            $table->boolean('deduct_from_gross');
            $table->boolean('consider_consecutive');
            $table->integer('missed_out_limit');
            $table->integer('adjust_days');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missed_out_time_deduction_policies');
    }
};
