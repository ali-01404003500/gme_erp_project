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
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('salary_setup_id')->nullable()->constrained('salary_setups')->onDelete('cascade');
            $table->decimal('basic', 20, 5)->default(0);
            $table->decimal('house_rent', 20, 5)->default(0);
            $table->decimal('medical', 20, 5)->default(0);
            $table->decimal('conveyance', 20, 5)->default(0);
            $table->decimal('others', 20, 5)->default(0);
            $table->decimal('gross', 20, 5)->default(0);
            $table->date('effective_date');
            $table->decimal('tax', 20, 5)->default(0);
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
        Schema::dropIfExists('employee_salaries');
    }
};
