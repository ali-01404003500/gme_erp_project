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
        Schema::create('legal_entry_complainants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_entry_id')->constrained('legal_entries')->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->string('complainant_name');
            $table->string('complainant_designation')->nullable();
            $table->string('complainant_phone')->nullable();
            $table->string('complainant_father')->nullable();
            $table->string('complainant_nid')->nullable();
            $table->string('complainant_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_entry_complainants');
    }
};
