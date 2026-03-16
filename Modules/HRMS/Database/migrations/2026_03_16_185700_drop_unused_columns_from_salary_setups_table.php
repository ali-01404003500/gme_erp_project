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
        Schema::table('salary_setups', function (Blueprint $table) {
            $table->dropColumn([
                'is_others_basic',
                'is_conveyance_fixed',
                'is_medical_fixed',
                'is_others_fixed',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_setups', function (Blueprint $table) {
            $table->boolean('is_others_basic')->default(false)->after('others');
            $table->boolean('is_conveyance_fixed')->default(false)->after('conveyance');
            $table->boolean('is_medical_fixed')->default(false)->after('medical');
            $table->boolean('is_others_fixed')->default(false)->after('others');
        });
    }
};
