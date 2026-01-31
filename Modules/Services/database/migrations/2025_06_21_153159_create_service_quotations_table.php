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
        Schema::create('service_quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained('services');
            $table->foreignId('service_token_id')->nullable()->constrained('service_tokens')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->string('quotation_no');
            $table->date('date');
            $table->decimal('total_amount', 20, 5);
            $table->decimal('discount', 20, 5);
            $table->decimal('percentage', 20, 5);
            $table->decimal('total', 20, 5);
            $table->decimal('net_amount', 20, 5);
            $table->string('remarks')->nullable();
            $table->tinyInteger('status')->default(0);            
            $table->softDeletes();
            $table->foreignId('approved_by')->nullable()->constrained('users');
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
        Schema::dropIfExists('service_quotations');
    }
};
