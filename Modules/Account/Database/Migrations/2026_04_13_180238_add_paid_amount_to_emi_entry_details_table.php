<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('e_m_i_entry_details', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)
                  ->default(0)
                  ->after('emi_amount');
        });
    }

    public function down(): void
    {
        Schema::table('e_m_i_entry_details', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }
};
