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
        Schema::create('generated_vendor_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_id')->constrained('vendor_bill_settings')->onDelete('cascade');
            $table->string('bill_id')->unique(); // e.g. BILL-2025-0001
            $table->morphs('bill_for'); // Same as setting: vendor, employee, etc.
            $table->date('bill_date');
            $table->decimal('amount', 10, 2);
            $table->string('document_path')->nullable();
            $table->string('status')->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('SET NULL');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('SET NULL');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_vendor_bills');
    }
};
