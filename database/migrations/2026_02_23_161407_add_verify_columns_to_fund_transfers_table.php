<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('fund_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('verify_by')->nullable()->after('attachments');
            $table->dateTime('verify_date')->nullable()->after('verify_by');
        });
    }

    public function down()
    {
        Schema::table('fund_transfers', function (Blueprint $table) {
            $table->dropColumn(['verify_by', 'verify_date']);
        });
    }
};
