<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_settings', function (Blueprint $table) {
            $table->longText('dms_ledger_files')
                ->nullable()
                ->after('ledger_files');
        });
    }

    public function down(): void
    {
        Schema::table('customer_settings', function (Blueprint $table) {
            $table->dropColumn('dms_ledger_files');
        });
    }
};
