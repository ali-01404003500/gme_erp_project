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
        Schema::create('e_m_i_entry_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'emi_entry_id')->constrained('e_m_i_entries')->onDelete('cascade');
            $table->date(column: 'emi_date');
            $table->double(column: 'interest_amount')->default(0);
            $table->double(column: 'principal_amount')->default(0);
            $table->double(column: 'emi_amount')->default(0);
            $table->string( 'status')->default('due');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_m_i_entry_details');
    }
};
