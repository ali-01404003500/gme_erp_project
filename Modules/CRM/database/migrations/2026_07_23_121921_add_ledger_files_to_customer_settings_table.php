<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_settings', function (Blueprint $table) {
            $table->json('ledger_files')
                ->nullable()
                ->after('opening_balance');
        });
    }

    public function down(): void
    {
        Schema::table('customer_settings', function (Blueprint $table) {
            $table->dropColumn('ledger_files');
        });
    }
};
