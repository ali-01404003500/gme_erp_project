<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // present_address nullable করতে
            if (Schema::hasColumn('employees', 'present_address')) {
                $table->string('present_address')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // আগের state ফেরত (nullable false)
            if (Schema::hasColumn('employees', 'present_address')) {
                $table->string('present_address')->nullable(false)->change();
            }
        });
    }
};
