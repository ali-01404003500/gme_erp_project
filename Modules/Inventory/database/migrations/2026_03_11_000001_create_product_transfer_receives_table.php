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
        Schema::create('product_transfer_receives', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->date('receive_date');
            $table->foreignId('product_transfer_id')->constrained('product_transfers')->onDelete('cascade');
            $table->foreignId('source_warehouse_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('destination_warehouse_id')->constrained('branches')->onDelete('cascade');
            $table->text('receive_description')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('deleted_by')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_transfer_receives');
    }
};
