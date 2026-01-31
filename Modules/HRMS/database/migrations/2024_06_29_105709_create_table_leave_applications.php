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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained('leave_types')->onDelete('cascade');
            $table->integer('day_count');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('from_date_leave_count')->default('full_day')->comment('full_day, first_half_day, last_half_day');
            $table->string('to_date_leave_count')->default('full_day')->comment('full_day, first_half_day, last_half_day');
            $table->string('remarks')->nullable();
            $table->string('status')->default('pending')->comment('pending, approved, rejected, recommended');
            $table->json('file_uploads')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('approved_comments')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('rejected_comments')->nullable();
            $table->foreignId('recommended_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('recommended_comments')->nullable();

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
        Schema::dropIfExists('table_leave_applications');
    }
};
