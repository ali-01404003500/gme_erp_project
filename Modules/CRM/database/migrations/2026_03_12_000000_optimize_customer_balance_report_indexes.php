<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds critical indexes to optimize the Customer Balance Details Report
     * to load in under 1 second.
     */
    public function up(): void
    {
        // =====================================================
        // SALES_ORDERS TABLE INDEXES
        // =====================================================
        // Composite index for the main report query filtering by customer_id, 
        // status, and deleted_at, then grouping by customer_id
        if (!Schema::hasIndex('sales_orders', 'sales_orders_customer_status_deleted_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index(['customer_id', 'status', 'deleted_at'], 'sales_orders_customer_status_deleted_idx');
            });
        }

        // Index for invoice_date filtering (used in date range queries)
        if (!Schema::hasIndex('sales_orders', 'sales_orders_invoice_date_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index('invoice_date', 'sales_orders_invoice_date_idx');
            });
        }

        // Composite index for date-based customer queries
        if (!Schema::hasIndex('sales_orders', 'sales_orders_customer_invoice_date_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index(['customer_id', 'invoice_date'], 'sales_orders_customer_invoice_date_idx');
            });
        }

        // =====================================================
        // TRANSACTIONS TABLE INDEXES
        // =====================================================
        // Critical composite index for credit amount aggregation by account_id
        if (!Schema::hasIndex('transactions', 'transactions_account_balance_type_deleted_idx')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->index(['account_id', 'balance_type', 'deleted_at'], 'transactions_account_balance_type_deleted_idx');
            });
        }

        // Index for date-based filtering on transactions
        if (!Schema::hasIndex('transactions', 'transactions_created_at_idx')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->index('created_at', 'transactions_created_at_idx');
            });
        }

        // Composite index for account + date queries (most common query pattern)
        if (!Schema::hasIndex('transactions', 'transactions_account_created_at_idx')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->index(['account_id', 'created_at'], 'transactions_account_created_at_idx');
            });
        }

        // =====================================================
        // CUSTOMER_SETTINGS TABLE INDEXES
        // =====================================================
        // Index for quick opening_balance lookup by customer_id
        if (!Schema::hasIndex('customer_settings', 'customer_settings_customer_id_idx')) {
            Schema::table('customer_settings', function (Blueprint $table) {
                $table->index('customer_id', 'customer_settings_customer_id_idx');
            });
        }

        // =====================================================
        // U_S_G_OR_O_P_G_LICENSE_REQUISITIONS TABLE INDEXES
        // =====================================================
        // Index for machine_code filter (checking existence by customer_id)
        if (!Schema::hasIndex('u_s_g_or_o_p_g_license_requisitions', 'license_requisitions_customer_deleted_idx')) {
            Schema::table('u_s_g_or_o_p_g_license_requisitions', function (Blueprint $table) {
                $table->index(['customer_id', 'deleted_at'], 'license_requisitions_customer_deleted_idx');
            });
        }

        // =====================================================
        // CUSTOMERS TABLE INDEXES
        // =====================================================
        // Index for company_place_id (used in area joins)
        if (!Schema::hasIndex('customers', 'customers_company_place_id_idx')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->index('company_place_id', 'customers_company_place_id_idx');
            });
        }

        // Index for status filtering (actived customers)
        if (!Schema::hasIndex('customers', 'customers_status_idx')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->index('status', 'customers_status_idx');
            });
        }

        // Composite index for status + company_place_id queries
        if (!Schema::hasIndex('customers', 'customers_status_company_place_idx')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->index(['status', 'company_place_id'], 'customers_status_company_place_idx');
            });
        }

        // =====================================================
        // ACCOUNTS TABLE INDEXES
        // =====================================================
        // Composite index for polymorphic relationship lookup
        if (!Schema::hasIndex('accounts', 'accounts_accountable_type_id_idx')) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->index(['accountable_type', 'accountable_id'], 'accounts_accountable_type_id_idx');
            });
        }

        // Index for account_subsidiary_id filtering
        if (!Schema::hasIndex('accounts', 'accounts_subsidiary_id_idx')) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->index('account_subsidiary_id', 'accounts_subsidiary_id_idx');
            });
        }

        // Composite index for the specific query pattern in the report
        if (!Schema::hasIndex('accounts', 'accounts_type_id_subsidiary_idx')) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->index(['accountable_type', 'accountable_id', 'account_subsidiary_id'], 'accounts_type_id_subsidiary_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sales Orders
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropIndex('sales_orders_customer_status_deleted_idx');
            $table->dropIndex('sales_orders_invoice_date_idx');
            $table->dropIndex('sales_orders_customer_invoice_date_idx');
        });

        // Transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('transactions_account_balance_type_deleted_idx');
            $table->dropIndex('transactions_created_at_idx');
            $table->dropIndex('transactions_account_created_at_idx');
        });

        // Customer Settings
        Schema::table('customer_settings', function (Blueprint $table) {
            $table->dropIndex('customer_settings_customer_id_idx');
        });

        // License Requisitions
        Schema::table('u_s_g_or_o_p_g_license_requisitions', function (Blueprint $table) {
            $table->dropIndex('license_requisitions_customer_deleted_idx');
        });

        // Customers
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_company_place_id_idx');
            $table->dropIndex('customers_status_idx');
            $table->dropIndex('customers_status_company_place_idx');
        });

        // Accounts
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropIndex('accounts_accountable_type_id_idx');
            $table->dropIndex('accounts_subsidiary_id_idx');
            $table->dropIndex('accounts_type_id_subsidiary_idx');
        });
    }
};
