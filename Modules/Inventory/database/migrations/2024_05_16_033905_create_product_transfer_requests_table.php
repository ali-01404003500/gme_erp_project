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
        Schema::create('product_transfer_requests', function (Blueprint $table) {
            $table->id();
            $table->date('request_date')->nullable(false);
            $table->text('remarks')->nullable();
            $table->foreignId('source_branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('destination_branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('status')->default('pending')->comment('pending, approved, rejected, recommended');

            $table->timestamps();

            $table->softDeletes();
            // created_by and updated_by
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_transfer_requests');
    }
};
