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
        Schema::create('legal_entry_witnesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_entry_id')->constrained('legal_entries')->onDelete('cascade');
            $table->string('witness_name')->nullable();
            $table->string('witness_father_name')->nullable();
            $table->string('witness_mother_name')->nullable();
            $table->string('witness_address')->nullable();
            $table->string('witness_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_entry_witnesses');
    }
};
