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
        Schema::create('acc_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no')->uniqid()->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('purchase_id');
            $table->float('quantity', 16, 2)->default(0);
            $table->decimal('price', 16, 2)->default(0);
            $table->decimal('amount', 16, 2)->virtualAs('quantity * price');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->foreign('purchase_id')->references('id')->on('acc_purchases')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acc_purchase_details');
    }
};
