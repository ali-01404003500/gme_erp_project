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
        Schema::table('products', function (Blueprint $table) {
            //
             $table->decimal('cost_price', 15, 2)->nullable()->change();
            $table->decimal('landed_price', 15, 2)->nullable()->change();
            $table->decimal('transportation_cost', 15, 2)->nullable()->change();
            $table->decimal('vat', 15, 2)->nullable()->change();
            $table->decimal('tax', 15, 2)->nullable()->change();
            $table->decimal('misc', 15, 2)->nullable()->change();
            $table->decimal('total_price', 15, 2)->nullable()->change();
            $table->decimal('last_cost_price', 15, 2)->nullable()->change();
            $table->decimal('max_sales_quantity', 15, 2)->nullable()->change();
            $table->decimal('min_discount', 15, 2)->default(0.00)->change();
            $table->decimal('max_discount', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
