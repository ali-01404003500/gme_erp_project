<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // personal_mobile nullable করতে
            if (Schema::hasColumn('employees', 'personal_mobile')) {
                $table->string('personal_mobile')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // আগের state এ ফেরত (nullable false)
            if (Schema::hasColumn('employees', 'personal_mobile')) {
                $table->string('personal_mobile')->nullable(false)->change();
            }
        });
    }
};
