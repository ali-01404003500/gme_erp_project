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
        Schema::create('bills_and_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('date_of_bill_claim');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('approved_comments')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('rejected_comments')->nullable();
            $table->foreignId('recommended_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('recommended_comments')->nullable();
            $table->string('status')->default('pending')->comment('pending, approved, rejected, recommended');

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
        Schema::dropIfExists('bills_and_allowances');
    }
};
