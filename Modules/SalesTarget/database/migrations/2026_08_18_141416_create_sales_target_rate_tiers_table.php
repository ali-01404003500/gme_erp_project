<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_target_rate_tiers', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_percent', 6, 2);
            $table->decimal('max_percent', 6, 2)->nullable();
            $table->decimal('rate_percent', 6, 2); // achieved_amount er upor kotota % incentive
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_target_rate_tiers');
    }
};
