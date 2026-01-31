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
        Schema::create('contact_persion_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("branch_id")->constrained('branches')->cascadeOnDelete();
            $table->string('name');
            $table->string('mobile');
            $table->string('designation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_persion_details');
    }
};
