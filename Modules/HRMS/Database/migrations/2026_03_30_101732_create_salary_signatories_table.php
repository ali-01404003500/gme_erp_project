<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('salary_signatories')) {
            Schema::create('salary_signatories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
                $table->string('signatory_tag');
                $table->string('approver_level');
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->text('description')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();

                $table->index(['approver_level', 'status']);
                $table->unique(['approver_level', 'status']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('salary_signatories');
    }
};
