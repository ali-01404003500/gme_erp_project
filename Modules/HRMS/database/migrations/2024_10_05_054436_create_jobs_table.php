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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->required()->constrained('branches')->cascadeOnDelete();
            $table->foreignId('department_id')->required()->constrained('departments')->cascadeOnDelete();
            $table->foreignId('designation_id')->required()->constrained('designations')->cascadeOnDelete();
            $table->string('title')->required();
            $table->string('job_type')->required();
            $table->string('gender')->required();
            $table->string('office_hours')->nullable();
            $table->string('weekend')->nullable();
            $table->date('start_at')->nullable();
            $table->date('deadline_at')->nullable();
            $table->string('salary')->nullable();
            $table->string('location')->nullable();
            $table->longText('description')->nullable();
            $table->longText('company_overview')->nullable();
            $table->longText('experience')->nullable();
            $table->longText('employee_centric_policy')->nullable();
            $table->longText('educational_requirement')->nullable();
            $table->longText('responsibility')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
