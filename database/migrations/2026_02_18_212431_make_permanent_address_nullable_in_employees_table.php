<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // permanent_address nullable করতে
            if (Schema::hasColumn('employees', 'permanent_address')) {
                $table->string('permanent_address')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // আগের state ফেরত (nullable false)
            if (Schema::hasColumn('employees', 'permanent_address')) {
                $table->string('permanent_address')->nullable(false)->change();
            }
        });
    }
};
