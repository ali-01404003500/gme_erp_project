<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function ($table) {

            // Rename remarks → check_in_remarks if it exists
            if (Schema::hasColumn('attendances', 'remarks')) {
                DB::statement('ALTER TABLE `attendances` CHANGE `remarks` `check_in_remarks` TEXT NULL');
            }

            // Add check_out_remarks column
            if (!Schema::hasColumn('attendances', 'check_out_remarks')) {
                $table->text('check_out_remarks')->nullable()->after('check_in_remarks');
            }

            // Add flag column
            if (!Schema::hasColumn('attendances', 'flag')) {
                $table->string('flag')->nullable()->after('check_out_remarks');
            }
        });
    }

    public function down()
    {
        Schema::table('attendances', function ($table) {

            // Rollback rename
            if (Schema::hasColumn('attendances', 'check_in_remarks')) {
                DB::statement('ALTER TABLE `attendances` CHANGE `check_in_remarks` `remarks` TEXT NULL');
            }

            // Drop added columns
            if (Schema::hasColumn('attendances', 'check_out_remarks')) {
                $table->dropColumn('check_out_remarks');
            }

            if (Schema::hasColumn('attendances', 'flag')) {
                $table->dropColumn('flag');
            }
        });
    }
};
