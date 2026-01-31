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
       

        // Add account_head_id to transport_expenses table
        Schema::table('transport_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('account_head_id')->nullable()->after('final_approved_amount');
            $table->foreign('account_head_id')->references('id')->on('accounts')->onDelete('set null');
            $table->index('account_head_id');
        });

        // Add account_head_id to general_expenses table
        Schema::table('general_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('account_head_id')->nullable()->after('final_approved_amount');
            $table->foreign('account_head_id')->references('id')->on('accounts')->onDelete('set null');
            $table->index('account_head_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        

        Schema::table('transport_expenses', function (Blueprint $table) {
            $table->dropForeign(['account_head_id']);
            $table->dropColumn('account_head_id');
        });

        Schema::table('general_expenses', function (Blueprint $table) {
            $table->dropForeign(['account_head_id']);
            $table->dropColumn('account_head_id');
        });
    }
};