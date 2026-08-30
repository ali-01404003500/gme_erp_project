<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // আগে চেক করুন: SELECT * FROM purchase_order_details WHERE quantity NOT REGEXP '^[0-9]+(\.[0-9]+)?$' OR price NOT REGEXP '^[0-9]+(\.[0-9]+)?$';
        Schema::table('purchase_order_details', function (Blueprint $table) {
            $table->decimal('quantity', 12, 2)->default(0)->change();
            $table->decimal('price', 14, 4)->default(0)->change();
            $table->decimal('amount', 16, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('purchase_order_details', function (Blueprint $table) {
            $table->string('quantity')->change();
            $table->string('price')->change();
            $table->string('amount')->change();
        });
    }
};
