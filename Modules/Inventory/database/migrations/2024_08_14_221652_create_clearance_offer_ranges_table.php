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
        Schema::create('clearance_offer_ranges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_detail_id')->constrained('offer_details')->onDelete('cascade');
            $table->decimal('buying_amount_from', 10, 2);
            $table->decimal('buying_amount_to', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clearance_offer_ranges');
    }
};
