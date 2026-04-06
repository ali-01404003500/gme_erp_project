<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_verifications', function (Blueprint $table) {
            $table->id(); // id 
            $table->bigInteger('salary_id'); 
            $table->bigInteger('payroll_id'); // reference to salary 
            $table->integer('approver_level');       // approval level
            $table->string('reference_type');   
            $table->bigInteger('approver_id');   // reference to employee
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // verification status
            $table->timestamp('approved_at')->nullable(); // when verified
            $table->timestamps(); // created_at & updated_at

        });
    }   

    public function down(): void
    {
        Schema::dropIfExists('salary_verifications');
    }
};
