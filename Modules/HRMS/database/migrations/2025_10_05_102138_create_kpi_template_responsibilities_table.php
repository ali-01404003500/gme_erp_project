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
        Schema::create('kpi_template_responsibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_template_id')->constrained('kpi_templates')->onDelete('cascade');
            $table->foreignId('responsibility_entriy_id')->constrained('responsibility_entries')->onDelete('cascade');
            $table->integer('weight')->nullable();
            $table->integer('time')->nullable();
            $table->string('frequency')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_template_responsibilities');
    }
};
