<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if column exists
        if (Schema::hasColumn('attendances', 'remarks')) {
            DB::statement('ALTER TABLE `attendances` CHANGE `remarks` `check_in_remarks` TEXT NULL');
        }
    }

    public function down()
    {
        if (Schema::hasColumn('attendances', 'check_in_remarks')) {
            DB::statement('ALTER TABLE `attendances` CHANGE `check_in_remarks` `remarks` TEXT NULL');
        }
    }
};
