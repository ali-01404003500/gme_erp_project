<?php
// database/migrations/2026_03_28_000002_create_salary_approval_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryApprovalRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('salary_approval_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_generate_id')->constrained('salary_generates')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->enum('status', ['pending', 'approved', 'denied'])->default('pending');
            $table->integer('current_level')->default(1);
            $table->text('remarks')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('current_level');
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_approval_requests');
    }
}