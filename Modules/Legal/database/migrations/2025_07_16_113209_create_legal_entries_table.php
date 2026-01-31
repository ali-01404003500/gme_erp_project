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
        Schema::create('legal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('legal_id');
            $table->date('date');
            $table->decimal('amount', 15, 2);
            $table->string('legal_type');
            $table->string('case_no')->nullable();;
            $table->text('occurrence_info')->nullable();
            $table->date('occurrence_date')->nullable();
            $table->text('legal_description')->nullable();
            $table->string('advocate_name')->nullable();
            $table->string('advocate_designation')->nullable();
            $table->string('advocate_phone')->nullable();
            $table->string('advocate_address')->nullable();
            $table->string('status')->default('running');
            $table->json('attachment')->nullable();

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
        Schema::dropIfExists('legal_entries');
    }
};
