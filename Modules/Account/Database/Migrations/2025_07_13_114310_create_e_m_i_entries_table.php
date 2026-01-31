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
        Schema::create('e_m_i_entries', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'emi_number');
            $table->foreignId(column: 'customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId(column: 'sales_order_id')->nullable()->constrained('sales_orders')->onDelete('cascade');
            $table->double(column: 'emi_amount')->default(0);
            $table->string('tenure_type')->nullable();
            $table->integer('tenure_no')->nullable();
            $table->double('interest_rate')->default(0);
            $table->date( 'start_date');
            $table->string( 'status')->default('due');

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
        Schema::dropIfExists('e_m_i_entries');
    }
};
