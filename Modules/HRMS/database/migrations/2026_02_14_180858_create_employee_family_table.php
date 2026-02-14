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
        Schema::create('employee_family', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete(); // Employee relation
            $table->string('name', 255);
            $table->string('relationship', 100);
            $table->enum('gender', ['Male','Female','Other']);
            $table->string('nid', 50)->nullable();
            $table->string('profession', 150)->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->timestamps();
            $table->softDeletes(); // Soft delete optional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_family');
    }
};
