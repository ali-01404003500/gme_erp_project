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
        Schema::create('issue_products', function (Blueprint $table) {
            $table->id();
            $table->string('issue_date')->nullable();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('purpose_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->string('order_number')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('issue_products');
    }
};
