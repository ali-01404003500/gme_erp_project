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
        Schema::create('legal_bill_entries', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no');
            $table->date('date');
            $table->foreignId('vendor_id')->cosntrained('vendors')->onDelete('cascade');
            $table->foreignId('legal_entry_id')->constrained('legal_entries')->onDelete('cascade');
            $table->string('particular', 255)->nullable();
            $table->decimal('amount', 15, 2);
            $table->json('attachment')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('status')->default('pending');

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
        Schema::dropIfExists('legal_bill_entries');
    }
};
