<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('i_o_u_requisition_entries', function (Blueprint $table) {
            $table->decimal('verify_amount', 15, 2)->nullable()->after('request_amount');
        });
    }

    public function down(): void
    {
        Schema::table('i_o_u_requisition_entries', function (Blueprint $table) {
            $table->dropColumn('verify_amount');
        });
    }
};

