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
        Schema::create('daily_legal_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained('customers');
            $table->string('task_type');
            $table->foreignId('assign_to')->constrained('employees');
            $table->string('status')->default('pending');

            $table->text('assign_remarks')->nullable();
            $table->text('complete_remarks')->nullable();

            $table->unsignedBigInteger('complete_by')->nullable();
            $table->date('complete_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_legal_tasks');
    }
};
