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
        Schema::create('office_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->date('date');
            $table->string('invoice_no');
            $table->string('reference_bill');
            $table->string('particular');
            $table->double('bill_amount');
            $table->string('remarks')->nullable();
            $table->string('file_upload')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_purchases');
    }
};
