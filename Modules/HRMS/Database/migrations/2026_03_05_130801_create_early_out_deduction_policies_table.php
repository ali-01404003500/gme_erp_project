<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('early_out_deduction_policies', function (Blueprint $table) {
            $table->id();
            $table->boolean('consider_early_out');
            $table->boolean('deduct_from_gross');
            $table->boolean('consider_consecutive_early_out');
            $table->integer('early_out_limit');
            $table->integer('adjust_days');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('early_out_deduction_policies');
    }
};
