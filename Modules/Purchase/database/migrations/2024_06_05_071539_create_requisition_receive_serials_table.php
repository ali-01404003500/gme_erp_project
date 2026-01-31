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
        Schema::create('requisition_receive_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_id')->nullable()->constrained('requisitions')->cascadeOnDelete();
            $table->foreignId('requisition_receive_id')->nullable()->constrained('requisition_receives')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('product_catalogs')->cascadeOnDelete();
            $table->string('serial_no')->nullable();
            $table->string('dongle_no')->nullable();
            $table->string('manufacture_date')->nullable();
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
        Schema::dropIfExists('requisition_receive_serials');
    }
};
