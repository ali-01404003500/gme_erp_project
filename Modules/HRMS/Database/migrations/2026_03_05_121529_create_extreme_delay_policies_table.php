<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extreme_delay_policies', function (Blueprint $table) {
            $table->id();
            $table->boolean('consider_extreme_delay');
            $table->boolean('deduct_from_salary');
            $table->boolean('consider_consecutive_extreme_delay');
            $table->integer('extreme_delay_limit');
            $table->integer('adjust_days');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extreme_delay_policies');
    }
};
