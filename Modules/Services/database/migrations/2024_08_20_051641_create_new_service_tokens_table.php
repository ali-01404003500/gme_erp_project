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
        Schema::create('service_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('service_id')->nullable()->constrained('services');
            $table->string('customer_id')->nullable()->constrained('customers');
            $table->string('contact_person_phone')->nullable();
            $table->date('token_date')->nullable();
            $table->date('invoice_id')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->string('product_id')->nullable()->constrained('products');
            $table->string('serial_number')->nullable();
            $table->string('service_type')->nullable();
            $table->text('problem_details')->nullable();
            $table->string('problem_type')->nullable();
            $table->string('work_type')->nullable();
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
        Schema::dropIfExists('service_tokens');
    }
};
