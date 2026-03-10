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
        Schema::create('underwork_deduction_policies', function (Blueprint $table) {
            $table->id();
            $table->boolean('consider_underwork');                  
            $table->boolean('consider_cumulative');                  
            $table->boolean('deduct_from_salary');                   
            $table->unsignedBigInteger('leave_type_id')->nullable();
            $table->integer('hours_to_consider');                    
            $table->integer('adjust_days');                         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('underwork_deduction_policies');
    }
};
