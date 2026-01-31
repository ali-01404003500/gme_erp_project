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
        Schema::create('legal_entry_convicts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_entry_id')->constrained('legal_entries')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('convict_name');
            $table->string('convict_designation')->nullable();
            $table->string('convict_phone')->nullable();
            $table->string('father_or_husband')->nullable();
            $table->string('convict_father_name')->nullable();
            $table->string('convict_mother_name')->nullable();
            $table->string('convict_nid')->nullable();
            $table->string('convict_address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_entry_convicts');
    }
};
