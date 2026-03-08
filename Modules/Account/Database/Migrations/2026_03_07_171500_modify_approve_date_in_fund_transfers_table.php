<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        // 1️⃣ Drop foreign key with raw SQL
        DB::statement('ALTER TABLE `fund_transfers` DROP FOREIGN KEY `fund_transfers_approve_date_foreign`');

        // 2️⃣ Modify column to normal timestamp
        DB::statement('ALTER TABLE `fund_transfers` MODIFY `approve_date` TIMESTAMP NULL');
    }

    public function down()
    {
        // Revert back to foreign key (bigint)
        DB::statement('ALTER TABLE `fund_transfers` MODIFY `approve_date` BIGINT NULL');
        DB::statement('ALTER TABLE `fund_transfers` ADD CONSTRAINT `fund_transfers_approve_date_foreign` FOREIGN KEY (`approve_date`) REFERENCES `users`(`id`) ON DELETE SET NULL');
    }
};
