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
        Schema::create('i_o_u_requisition_entries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade'); // Authenticated employee
            $table->string('type'); // Expense / Advance
            $table->decimal('request_amount', 15, 3);
            $table->decimal('approved_amount', 15, 3)->nullable();
            $table->decimal('received_amount', 15, 3)->default(0);
            $table->text('remarks')->nullable();
            $table->string('status')->default('pending');

            $table->timestamps();

            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('SET NULL');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('SET NULL');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_o_u_requisition_entries');
    }
};
