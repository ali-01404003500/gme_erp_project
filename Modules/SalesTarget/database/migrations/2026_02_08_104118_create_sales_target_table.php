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
        Schema::create('sales_target', function (Blueprint $table) {
            $table->id('target_id');
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            // Monthly columns
            $table->decimal('jan_target', 15, 2)->default(0);
            $table->decimal('feb_target', 15, 2)->default(0);
            $table->decimal('mar_target', 15, 2)->default(0);
            $table->decimal('apr_target', 15, 2)->default(0);
            $table->decimal('may_target', 15, 2)->default(0);
            $table->decimal('jun_target', 15, 2)->default(0);
            $table->decimal('jul_target', 15, 2)->default(0);
            $table->decimal('aug_target', 15, 2)->default(0);
            $table->decimal('sep_target', 15, 2)->default(0);
            $table->decimal('oct_target', 15, 2)->default(0);
            $table->decimal('nov_target', 15, 2)->default(0);
            $table->decimal('dec_target', 15, 2)->default(0);

            $table->decimal('total_target', 15, 2)->default(0);
            $table->year('year')->default(date('Y'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_target');
    }
};
