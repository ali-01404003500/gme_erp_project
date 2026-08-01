<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_stocks', function (Blueprint $table) {
            $table->date('expire_date')->nullable()->after('lot_no');
            $table->date('warranty_date')->nullable()->after('serial_no');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_stocks', function (Blueprint $table) {
            $table->dropColumn([
                'expire_date',
                'warranty_date',
            ]);
        });
    }
};
