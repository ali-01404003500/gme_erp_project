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
        Schema::create('requisition_receive_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_receive_id')->constrained('requisition_receives')->cascadeOnDelete();
            $table->foreignId('requisition_id')->constrained('requisitions')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('product_catalogs')->cascadeOnDelete();
            $table->string('approved_quantity')->nullable();
            $table->string('received_quantity')->nullable();
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
        Schema::dropIfExists('requisition_receive_details');
    }
};
