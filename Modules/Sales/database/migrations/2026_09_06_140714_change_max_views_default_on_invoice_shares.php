<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE invoice_shares
            MODIFY max_views INT UNSIGNED NOT NULL DEFAULT 20
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE invoice_shares
            MODIFY max_views INT UNSIGNED NOT NULL DEFAULT 5
        ");
    }
};
