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
        Schema::create('advance_cheque_entries', function (Blueprint $table) {
            $table->id();
            $table->string('cheque_type');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('collection_date');
            $table->integer('no_of_cheque')->nullable();
            $table->foreignId('reference')->nullable()->constrained('e_m_i_entries')->onDelete('cascade');
            $table->string('remarks')->nullable();
            $table->string('document')->nullable();
            $table->decimal('total_amount', 15, 2);

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
        Schema::dropIfExists('advance_cheque_entries');
    }
};
