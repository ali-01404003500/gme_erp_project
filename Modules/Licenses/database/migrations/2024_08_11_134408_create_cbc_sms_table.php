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
        Schema::create('cbc_sms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('c_b_c_license_requisition_id')->constrained('c_b_c_license_requisitions')->onDelete('cascade')->name('cbc_license_requisition_id_foreign');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('dongle_id')->constrained('dongle_or_serial_entries')->onDelete('cascade');	
            $table->string('product_model')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->string('valid_period')->nullable();
            $table->string('valid_period_type')->nullable();
            $table->string('remarks')->nullable();
            $table->string('license_id')->nullable();
            $table->string('software_version')->nullable();
            $table->string('license_key')->nullable();
            $table->string('sms')->nullable();
            $table->string('status')->default('Pending');
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
        Schema::dropIfExists('cbc_sms');
    }
};
