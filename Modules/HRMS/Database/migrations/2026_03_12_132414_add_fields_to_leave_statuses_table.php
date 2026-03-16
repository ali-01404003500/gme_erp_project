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
        Schema::table('leave_statuses', function (Blueprint $table) {
            $table->string('leave_type')->nullable()->after('id');          // LEAVE TYPE
            $table->decimal('groupwise_balance',8,2)->default(0)->after('leave_type'); // Groupwise Bal.
            $table->decimal('remaining_balance',8,2)->default(0)->after('groupwise_balance'); // Remaining Bal.
            $table->decimal('balance_forwarded',8,2)->default(0)->after('remaining_balance'); // Bal. Forwarded
            $table->decimal('max_forward_balance',8,2)->default(0)->after('balance_forwarded'); // Max. F. Bal.
            $table->boolean('continuous')->default(false)->after('max_forward_balance'); // Continuous
            $table->boolean('continuous_sanction')->default(false)->after('continuous'); // Cont. Sanction
            $table->boolean('half_day')->default(false)->after('continuous_sanction'); // Half DAY
            $table->integer('max_sanction_per_year')->default(0)->after('half_day'); // Max. Sanc. YEAR
        });
    }

    public function down(): void
    {
        Schema::table('leave_statuses', function (Blueprint $table) {
            $table->dropColumn([
                'leave_type',
                'groupwise_balance',
                'remaining_balance',
                'balance_forwarded',
                'max_forward_balance',
                'continuous',
                'continuous_sanction',
                'half_day',
                'max_sanction_per_year',
            ]);
        });
    }
};
