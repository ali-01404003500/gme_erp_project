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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('currency', 10)->default('USD')->after('net_amount');
            $table->enum('incoterm', ['FOB', 'CIF', 'CFR', 'EXW'])->default('CIF')->after('currency');
            $table->decimal('booking_exchange_rate', 12, 4)->nullable()->after('incoterm');
            $table->unsignedBigInteger('approved_by')->nullable()->after('created_by');
            $table->timestamp('sent_to_supplier_at')->nullable()->after('approved_by');
            $table->timestamp('supplier_acknowledged_at')->nullable()->after('sent_to_supplier_at');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn([
                'currency', 'incoterm', 'booking_exchange_rate',
                'approved_by', 'sent_to_supplier_at', 'supplier_acknowledged_at',
            ]);
        });
    }
};
