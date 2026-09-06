<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{  
    public function up(): void
    {
        Schema::create('invoice_shares', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique();
            $table->unsignedBigInteger('sales_order_id'); // sales_orders.id বা bigint(20) unsigned এর সাথে ম্যাচ
            $table->foreign('sales_order_id')->references('id')->on('sales_orders')->cascadeOnDelete();
            $table->string('pdf_path');
            $table->string('customer_phone')->nullable();
            $table->unsignedInteger('max_views')->default(5);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('last_viewed_at')->nullable();
            $table->string('last_viewed_ip')->nullable();
            $table->boolean('is_revoked')->default(false);
            $table->timestamps();

            $table->index(['token', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_shares');
    }
};
