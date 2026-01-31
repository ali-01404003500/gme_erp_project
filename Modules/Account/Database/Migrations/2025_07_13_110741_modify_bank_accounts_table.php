<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->string('payment_mode')->nullable()->after('opening_balance');
            $table->foreignId('bank_id')->nullable()->change();
            $table->foreignId('bank_branch_id')->nullable()->change();
            $table->string('bank_account_no')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn('payment_mode');
            $table->foreignId('bank_id')->nullable(false)->change();
            $table->foreignId('bank_branch_id')->nullable(false)->change();
            $table->string('bank_account_no')->nullable(false)->change();
        });
    }
};