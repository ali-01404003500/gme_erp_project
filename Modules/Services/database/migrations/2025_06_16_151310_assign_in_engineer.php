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
        Schema::create('engineer_assigns_engineers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('engineer_assign_id')->constrained('engineer_assigns')->cascadeOnDelete();
            $table->foreignId('engineer_id')->constrained('employees')->cascadeOnDelete();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('engineer_assigns_engineers');
    }
};
