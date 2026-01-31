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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('assigned_engineer_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('service_date')->nullable();
            $table->string('service_unique_id')->nullable();

            $table->string('service_priority')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_assigned')->default(false);
            $table->string(column: 'action')->default('pending');

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
        Schema::dropIfExists('services');
    }
};
