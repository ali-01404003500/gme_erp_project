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
        Schema::create('vendor_bill_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->string('holder_type'); // ['Employee Account', 'Client Account', 'Vendor Account', 'Others Account']
            $table->morphs('bill_for'); // assuming vendors table
            $table->string('bill_type'); // ['Prepaid', 'Postpaid']
            $table->string('schedule_type'); // ['Daily', 'Monthly', 'Yearly', 'Static']
            $table->integer('schedule_value'); // e.g., every 1 month
            $table->date('start_date');
            $table->string('status')->default('Running'); // ['Running', 'Stop']
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_bill_settings');
    }
};
