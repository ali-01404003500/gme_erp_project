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
        Schema::table('make_payment_details', function (Blueprint $table) {

            $table->unsignedBigInteger('checked_by')->nullable()->after('updated_at');
            $table->date('checked_date')->nullable()->after('checked_by');

            $table->unsignedBigInteger('verified_by')->nullable()->after('checked_date');
            $table->date('verified_date')->nullable()->after('verified_by');

            $table->foreign('checked_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_payment_details', function (Blueprint $table) {
            //
        });
    }
};
