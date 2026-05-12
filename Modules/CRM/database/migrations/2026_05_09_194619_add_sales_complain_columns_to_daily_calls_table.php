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
        Schema::table('daily_calls', function (Blueprint $table) {
            $table->boolean('is_sales_complain')
                ->default(0)
                ->after('product_required_details');

            $table->text('sales_complain_details')
                ->nullable()
                ->after('is_sales_complain');
        });
    }

    public function down(): void
    {
        Schema::table('daily_calls', function (Blueprint $table) {
            $table->dropColumn(['is_sales_complain', 'sales_complain_details']);
        });
    }
};
