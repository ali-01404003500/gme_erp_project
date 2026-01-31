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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->cascadeOnDelete();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('requisition_no')->nullable();
            $table->text('description')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('discount')->nullable();
            $table->double('net_amount')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->json(column: 'file_uploads')->nullable();

            $table->foreignId('approved_by')->nullable()->constrained('users');

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
        Schema::dropIfExists('requisitions');
    }
};
