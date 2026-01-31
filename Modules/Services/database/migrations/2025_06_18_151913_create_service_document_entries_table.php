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
        Schema::create('service_document_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('product_catalogs')->onDelete('cascade');
            $table->string('document_number')->nullable();
            $table->date('document_date')->nullable();
            $table->string('documents')->nullable();
            $table->string('remarks')->nullable();

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
        Schema::dropIfExists('service_document_entries');
    }
};
