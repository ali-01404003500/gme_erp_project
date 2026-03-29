<?php
// database/migrations/2026_03_28_000001_create_salary_signatories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalarySignatoriesTable extends Migration
{
    public function up()
    {
        Schema::create('salary_signatories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('signatory_tag')->unique();
            $table->integer('level')->unsigned();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['level', 'status']);
            $table->unique(['level', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_signatories');
    }
}