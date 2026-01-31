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
        Schema::create('job_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->cascadeOnDelete();
            $table->foreignId('designation_id')->constrained('designations')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->string('office_hours')->nullable();
            $table->string('weekend')->nullable();
            $table->longText('description')->nullable();
            $table->longText('responsibility')->nullable();
            $table->longText('educational_requirement')->nullable();
            $table->longText('experience')->nullable();
            $table->longText('company_overview')->nullable();
            $table->string('location')->nullable();
            $table->string('salary')->nullable();
            $table->longText('employee_centric_policy')->nullable();
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
        Schema::dropIfExists('job_templates');
    }
};
