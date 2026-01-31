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
        // Change remarks field from VARCHAR to TEXT in quotations table
        if (Schema::hasColumn('quotations', 'remarks')) {
            Schema::table('quotations', function (Blueprint $table) {
                $table->text('remarks')->nullable()->change();
            });
        }

        // Change remarks field from VARCHAR to TEXT in service_quotations table
        if (Schema::hasColumn('service_quotations', 'remarks')) {
            Schema::table('service_quotations', function (Blueprint $table) {
                $table->text('remarks')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert remarks field from TEXT to string in quotations table
        if (Schema::hasColumn('quotations', 'remarks')) {
            Schema::table('quotations', function (Blueprint $table) {
                $table->string('remarks')->nullable()->change();
            });
        }

        // Revert remarks field from TEXT to string in service_quotations table
        if (Schema::hasColumn('service_quotations', 'remarks')) {
            Schema::table('service_quotations', function (Blueprint $table) {
                $table->string('remarks')->nullable()->change();
            });
        }
    }
};
