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
        Schema::create('daily_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->unsignedBigInteger('call_type_id')->nullable();
            $table->string('call_date');
            $table->tinyInteger('is_account_complain')->nullable();
            $table->text('complains_details')->nullable();
            $table->tinyInteger('is_service_complain')->nullable();
            $table->text('service_complain_details')->nullable();
            $table->tinyInteger('is_product_required')->nullable();
            $table->text('product_required_details')->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('daily_calls');
    }
};
