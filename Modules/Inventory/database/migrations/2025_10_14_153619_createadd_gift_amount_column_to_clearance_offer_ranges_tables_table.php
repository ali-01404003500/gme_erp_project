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
        Schema::table('clearance_offer_ranges', function (Blueprint $table) {
            $table->decimal('gift_amount', 10, 2)->nullable();
            $table->string('gift_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clearance_offer_ranges', function (Blueprint $table) {
            $table->dropColumn('gift_amount');
            $table->dropColumn('gift_type');
        });
    }
};
