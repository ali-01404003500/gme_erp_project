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
        Schema::create('customer_setting_fixed_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_setting_id')->constrained('customer_settings')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('product_catalogs')->cascadeOnDelete();
            $table->double('sales_amounts', 12, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_setting_fixed_discounts');
    }
};
