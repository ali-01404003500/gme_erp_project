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
         Schema::table('bills_and_allowances', function (Blueprint $table) {
            $table->unsignedBigInteger('petty_cash_payment_id')
                  ->nullable()
                  ->after('payment_date');

           
            $table->foreign('petty_cash_payment_id')
                  ->references('id')
                  ->on('petty_cash_payments')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills_and_allowances', function (Blueprint $table) {
            //
        });
    }
};
