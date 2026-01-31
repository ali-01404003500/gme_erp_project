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
        Schema::create('product_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('transfer_date'); // Ensure correct date type
            $table->unsignedBigInteger('source_warehouse_id');
            $table->unsignedBigInteger('destination_warehouse_id');
            $table->string('transfer_description')->nullable();
            $table->unsignedBigInteger('product_transfer_request_id')->nullable();
            $table->foreign('product_transfer_request_id')->references('id')->on('product_transfer_requests');
            $table->timestamps();

            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_transfers');
    }
};
