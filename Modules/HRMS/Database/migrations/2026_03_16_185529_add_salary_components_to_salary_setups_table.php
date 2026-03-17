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
            // House Rent
            
            $table->tinyInteger('is_house_rent_basic')->default(false)->after('house_rent')->default(0);

            // Conveyance 
            $table->tinyInteger('is_conveyance_basic')->default(false)->after('conveyance')->default(0);

            // Medical 
            $table->tinyInteger('is_medical_basic')->default(false)->after('medical')->default(0);
 

            // Entertainment
            $table->decimal('entertainment', 10, 2)->default(0)->after('is_medical_basic')->default(0);
            $table->tinyInteger('is_entertainment_basic')->default(false)->after('entertainment')->default(0);

            // Leave Fare
            $table->decimal('leave_fare', 10, 2)->default(0)->after('is_entertainment_basic')->default(0);
            $table->tinyInteger('is_leave_fare_basic')->default(false)->after('leave_fare')->default(0);

            // Utility
            $table->decimal('utility', 10, 2)->default(0)->after('is_leave_fare_basic')->default(0);
            $table->tinyInteger('is_utility_basic')->default(false)->after('utility')->default(0);

            // Unkeep
            $table->decimal('unkeep', 10, 2)->default(0)->after('is_utility_basic')->default(0);
            $table->tinyInteger('is_unkeep_basic')->default(false)->after('unkeep')->default(0);

            // Others 
            $table->tinyInteger('is_others_basic')->default(false)->after('others')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alary_setups', function (Blueprint $table) {
            $table->dropColumn([
                'is_house_rent_basic',
                'is_conveyance_basic',
                'is_medical_basic',
                'entertainment', 'is_entertainment_basic',
                'leave_fare', 'is_leave_fare_basic',
                'utility', 'is_utility_basic',
                'unkeep', 'is_unkeep_basic',
                'is_others_basic',
            ]);
        });
    }
};
