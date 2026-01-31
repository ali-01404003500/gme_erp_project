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
        Schema::create('requisition_receive_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_id')->nullable()->constrained('requisitions')->cascadeOnDelete();
            $table->foreignId('requisition_receive_id')->nullable()->constrained('requisition_receives')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('product_catalogs')->cascadeOnDelete();
            $table->string('batch_no')->nullable();
            $table->string('manufacture_no')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('expired_date')->nullable();
            $table->string('quantity')->nullable();
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
        Schema::dropIfExists('requisition_receive_batches');
    }
};
