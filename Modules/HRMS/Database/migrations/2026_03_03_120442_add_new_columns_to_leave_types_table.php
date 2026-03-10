<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->string('flag')->after('leave_type_name')->nullable();
            $table->string('half_flag')->after('flag')->nullable();
            $table->boolean('is_maternity')->default(0)->after('half_flag');
            $table->boolean('is_unpaid')->default(0)->after('is_maternity');
            $table->boolean('is_partially_balance')->default(0)->after('is_unpaid');
            $table->enum('leave_count_type', ['day', 'hour'])->default('day')->after('is_partially_balance');
        });
    }

    public function down(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->dropColumn(['flag', 'half_flag', 'is_maternity', 'is_unpaid', 'is_partially_balance', 'leave_count_type']);
        });
    }
};
