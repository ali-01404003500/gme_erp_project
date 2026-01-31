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
        Schema::create('job_application_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->required()->constrained('job_applications')->cascadeOnDelete();
            $table->string('institute');
            $table->string('examination');
            $table->string( 'result');
            $table->string('passing_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_application_educations');
    }
};
