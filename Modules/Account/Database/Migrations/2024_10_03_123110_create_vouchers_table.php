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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->string('description');
            $table->decimal('amount', 15);
            $table->date('date');
            $table->string('reference')->nullable();
            $table->string('voucher_type');
            $table->string('attachment')->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->unsignedBigInteger('branch_id')->nullable()->default(1);

            $table->timestamps();

            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->foreign('branch_id')->references('id')->on('branches');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
