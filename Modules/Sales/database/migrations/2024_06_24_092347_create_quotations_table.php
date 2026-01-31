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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_no');
            $table->date('date');
            $table->decimal('total_amount', 20, 5);
            $table->decimal('discount', 20, 5);
            $table->decimal('percentage', 20, 5);
            $table->decimal('total', 20, 5);
            $table->decimal('net_amount', 20, 5);
            $table->string('remarks')->nullable();
            $table->tinyInteger('status')->default(0);            
            $table->string('customer_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('area')->nullable();
            $table->string('address')->nullable();
            $table->foreignId('customer_type')->nullable()->constrained('customer_types');
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
        Schema::dropIfExists('quotations');
    }
};
