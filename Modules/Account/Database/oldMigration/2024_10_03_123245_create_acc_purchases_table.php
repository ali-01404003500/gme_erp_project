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
        Schema::create('acc_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->string('date');
            $table->string('invoice_no')->unique();

            $table->decimal('qty_total', 16, 2)->default(0);
            $table->decimal('qty_amount', 16, 2)->default(0);

            $table->decimal('discount_amount', 16, 2)->default(0);
            $table->decimal('total_amount', 16, 2)->default(0);
            $table->decimal('previous_due', 16, 2)->default(0);
            $table->decimal('payable_amount', 16, 2)->default(0);
            $table->decimal('paid_amount', 16, 2)->default(0);
            $table->decimal('due_amount', 16, 2)->default(0);

            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('supplier_id')->references('id')->on('acc_suppliers')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acc_purchases');
    }
};
