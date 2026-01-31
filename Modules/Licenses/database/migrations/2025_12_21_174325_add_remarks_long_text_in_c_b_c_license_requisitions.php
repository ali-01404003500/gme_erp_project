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
        Schema::table('c_b_c_license_requisitions', function (Blueprint $table) {
                      $table->longText('remarks')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('c_b_c_license_requisitions', function (Blueprint $table) {
                        $table->string('remarks')->nullable()->change();

        });
    }
};
