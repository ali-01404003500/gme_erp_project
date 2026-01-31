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
        Schema::create('daily_visit_plans', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->required();
            $table->string('phone_no')->required();
            $table->date('date')->required();
            $table->string('address')->required();
            $table->string('contact_person')->nullable();
            $table->string('business_type')->required();
            $table->string('visit_purpose')->nullable();
            $table->json('attachment')->nullable();
            $table->text('description')->required();
            $table->string('status')->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');

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
        Schema::dropIfExists('daily_visit_plans');
    }
};
