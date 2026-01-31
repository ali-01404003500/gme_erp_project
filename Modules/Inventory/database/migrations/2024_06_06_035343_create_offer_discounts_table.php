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
        Schema::create('offer_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_detail_id')->constrained('offer_details')->cascadeOnDelete();
            $table->string('discount_type');
            $table->decimal('discount_quentity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_discounts');
    }
};
