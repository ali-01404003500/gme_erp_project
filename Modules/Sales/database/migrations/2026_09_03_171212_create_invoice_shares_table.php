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
            $table->uuid('token')->unique(); // public URL এ এটাই থাকবে
            $table->foreignId('invoice_id')->constrained('sales_orders')->onDelete('cascade');
            $table->string('pdf_path'); // storage path (local বা s3)
            $table->string('customer_phone')->nullable(); // last-4 verify এর জন্য
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
