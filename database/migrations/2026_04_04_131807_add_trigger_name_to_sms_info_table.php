<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{ 

    public function up(): void
    {
        Schema::table('sms_info', function (Blueprint $table) {
            $table->integer('trigger_name')->nullable()->after('sms_mem_id');  
        });
    }

    public function down(): void
    {
        Schema::table('sms_info', function (Blueprint $table) {
            $table->dropColumn('trigger_name');
        });
    }


};
