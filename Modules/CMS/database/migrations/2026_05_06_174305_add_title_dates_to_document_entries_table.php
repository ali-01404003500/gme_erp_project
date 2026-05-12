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
        Schema::table('document_entries', function (Blueprint $table) {

            $table->string('title')->after('remarks')->nullable();
            $table->date('start_date')->after('title')->nullable();
            $table->date('expiry_date')->after('start_date')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('document_entries', function (Blueprint $table) {
            $table->dropColumn(['title', 'start_date', 'expiry_date']);
        });
    }
};
