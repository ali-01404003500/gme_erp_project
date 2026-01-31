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
        Schema::create('application_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->string('type');
            $table->date('date');
            $table->string('status')->default('pending');
            $table->text('description')->nullable();

            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('approval_note')->nullable();
            $table->foreignId('handover_by')->nullable()->constrained('users');
            $table->text('handover_note')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users');
            $table->text('received_note')->nullable();
            $table->foreignId('denied_by')->nullable()->constrained('users');
            $table->text('denied_note')->nullable();

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
        Schema::dropIfExists('application_entries');
    }
};
