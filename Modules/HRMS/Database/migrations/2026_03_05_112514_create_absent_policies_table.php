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
        Schema::create('absent_policies', function (Blueprint $table) {
            $table->id();
            $table->boolean('consider_absent');
            $table->boolean('deduct_from_salary');
            $table->boolean('deduct_from_gross');  
            $table->integer('adjust_days');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absent_policies');
    }
};
