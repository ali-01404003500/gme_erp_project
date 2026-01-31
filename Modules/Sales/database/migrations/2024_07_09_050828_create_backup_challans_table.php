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
        Schema::create('backup_challans', function (Blueprint $table) {
            $table->id();
            $table->date('remaining_date');
            $table->date('invoice_date');
            $table->string('invoice_no')->nullable();
            $table->string('type');
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('total_amount');
            $table->string('remarks')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('status')->nullable()->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('approved_comments')->nullable();
            $table->boolean('is_shipment')->default(0);

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
        Schema::dropIfExists('backup_challans');
    }
};
