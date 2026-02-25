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
        Schema::create('sales_incentive_slabs_settings', function (Blueprint $table) {
            $table->id();
            // Explicitly point to the custom table name 'sales_incentives_settings'
            $table->foreignId('sales_incentive_id')
                ->constrained('sales_incentives_settings')
                ->onDelete('cascade');
            $table->integer('min_range');
            $table->integer('max_range');
            $table->string('incentive_type');
            $table->decimal('incentive_rate', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_incentive_slabs_settings');
    }
};
