<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills_and_allowances', function (Blueprint $table) {
            // Team Leader verification
            $table->foreignId('checked_by_team_leader')->nullable()->after('status')->constrained('users')->onDelete('cascade');
            $table->timestamp('checked_by_team_leader_date')->nullable()->after('checked_by_team_leader');
            $table->text('checked_by_team_leader_comments')->nullable()->after('checked_by_team_leader_date');
            
            // HR/Accounts verification
            $table->foreignId('checked_by_accounts')->nullable()->after('checked_by_team_leader_comments')->constrained('users')->onDelete('cascade');
            $table->timestamp('checked_by_accounts_date')->nullable()->after('checked_by_accounts');
            $table->text('checked_by_accounts_comments')->nullable()->after('checked_by_accounts_date');
            
            // Final approval
            $table->foreignId('final_approved_by')->nullable()->after('checked_by_accounts_comments')->constrained('users')->onDelete('cascade');
            $table->timestamp('final_approved_date')->nullable()->after('final_approved_by');
            $table->text('final_approved_comments')->nullable()->after('final_approved_date');
            
            // Payment
            $table->foreignId('payment_by')->nullable()->after('final_approved_comments')->constrained('users')->onDelete('cascade');
            $table->timestamp('payment_date')->nullable()->after('payment_by');
            
            // Update status to include new verification stages
            $table->string('status')->default('pending')->comment('pending, team_leader_check, accounts_check, approved, rejected')->change();
        });
        
        // Add approved amounts to transport expenses
        Schema::table('transport_expenses', function (Blueprint $table) {
            $table->decimal('team_leader_approved_amount', 10, 2)->nullable()->after('settlement_amount');
            $table->decimal('accounts_approved_amount', 10, 2)->nullable()->after('team_leader_approved_amount');
            $table->decimal('final_approved_amount', 10, 2)->nullable()->after('accounts_approved_amount');
        });
        
        // Add approved amounts to general expenses
        Schema::table('general_expenses', function (Blueprint $table) {
            $table->decimal('team_leader_approved_amount', 10, 2)->nullable()->after('settlement_amount');
            $table->decimal('accounts_approved_amount', 10, 2)->nullable()->after('team_leader_approved_amount');
            $table->decimal('final_approved_amount', 10, 2)->nullable()->after('accounts_approved_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bills_and_allowances', function (Blueprint $table) {
            $table->dropForeign(['checked_by_team_leader']);
            $table->dropForeign(['checked_by_accounts']);
            $table->dropForeign(['final_approved_by']);
            $table->dropForeign(['payment_by']);
            
            $table->dropColumn([
                'checked_by_team_leader',
                'checked_by_team_leader_date',
                'checked_by_team_leader_comments',
                'checked_by_accounts',
                'checked_by_accounts_date',
                'checked_by_accounts_comments',
                'final_approved_by',
                'final_approved_date',
                'final_approved_comments',
                'payment_by',
                'payment_date'
            ]);
        });
        
        Schema::table('transport_expenses', function (Blueprint $table) {
            $table->dropColumn([
                'team_leader_approved_amount',
                'accounts_approved_amount',
                'final_approved_amount'
            ]);
        });
        
        Schema::table('general_expenses', function (Blueprint $table) {
            $table->dropColumn([
                'team_leader_approved_amount',
                'accounts_approved_amount',
                'final_approved_amount'
            ]);
        });
    }
};