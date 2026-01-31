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
        Schema::create('salary_setups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('effective_date');
            $table->decimal('basic', 20, 5)->default(0);
            $table->decimal('house_rent', 20, 5)->default(0);
            $table->decimal('medical', 20, 5)->default(0);
            $table->decimal('conveyance', 20, 5)->default(0);
            $table->decimal('others', 20, 5)->default(0);
            $table->tinyInteger('is_conveyance_fixed')->default(0);
            $table->tinyInteger('is_medical_fixed')->default(0);
            $table->tinyInteger('is_others_fixed')->default(0);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('salary_setups');
    }
};
