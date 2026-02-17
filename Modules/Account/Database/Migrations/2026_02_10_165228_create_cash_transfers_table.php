<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cash_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_employee_id');
            $table->unsignedBigInteger('to_employee_id');
            $table->decimal('amount', 15, 2);
            $table->date('transfer_date');
            $table->text('remarks')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, rejected
            $table->decimal('received_amount', 15, 2)->nullable();
            $table->boolean('is_cash_count_matched')->nullable();
            $table->timestamps();

            $table->foreign('from_employee_id')->references('id')->on('employees');
            $table->foreign('to_employee_id')->references('id')->on('employees');

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
        Schema::dropIfExists('cash_transfers');
    }
};
