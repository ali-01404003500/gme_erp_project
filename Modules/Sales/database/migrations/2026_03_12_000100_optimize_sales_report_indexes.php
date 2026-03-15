<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds critical indexes to optimize the Sales Report
     * to load in under 1 second.
     */
    public function up(): void
    {
        // =====================================================
        // SALES_ORDERS TABLE INDEXES
        // =====================================================
        
        // Composite index for invoice_date filtering (date range queries)
        if (!Schema::hasIndex('sales_orders', 'sales_orders_invoice_date_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index('invoice_date', 'sales_orders_invoice_date_idx');
            });
        }

        // Composite index for customer + date queries (most common filter combo)
        if (!Schema::hasIndex('sales_orders', 'sales_orders_customer_date_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index(['customer_id', 'invoice_date'], 'sales_orders_customer_date_idx');
            });
        }

        // Index for status filtering
        if (!Schema::hasIndex('sales_orders', 'sales_orders_status_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index('status', 'sales_orders_status_idx');
            });
        }

        // Composite index for status + date queries
        if (!Schema::hasIndex('sales_orders', 'sales_orders_status_date_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index(['status', 'invoice_date'], 'sales_orders_status_date_idx');
            });
        }

        // Index for sales_type filtering
        if (!Schema::hasIndex('sales_orders', 'sales_orders_sales_type_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index('sales_type', 'sales_orders_sales_type_idx');
            });
        }

        // Index for created_by (user) filtering
        if (!Schema::hasIndex('sales_orders', 'sales_orders_created_by_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index('created_by', 'sales_orders_created_by_idx');
            });
        }

        // Index for sales_order_id (invoice ID) searches
        if (!Schema::hasIndex('sales_orders', 'sales_orders_sales_order_id_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index('sales_order_id', 'sales_orders_sales_order_id_idx');
            });
        }

        // Composite index for customer + status (common filter combination)
        if (!Schema::hasIndex('sales_orders', 'sales_orders_customer_status_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index(['customer_id', 'status'], 'sales_orders_customer_status_idx');
            });
        }

        // =====================================================
        // SALES_ORDER_DETAILS TABLE INDEXES
        // =====================================================
        
        // Index for product_id filtering (used in hasManyThrough queries)
        if (!Schema::hasIndex('sales_order_details', 'sales_order_details_product_id_idx')) {
            Schema::table('sales_order_details', function (Blueprint $table) {
                $table->index('product_id', 'sales_order_details_product_id_idx');
            });
        }

        // Composite index for sales_order_id + product_id
        if (!Schema::hasIndex('sales_order_details', 'sales_order_details_order_product_idx')) {
            Schema::table('sales_order_details', function (Blueprint $table) {
                $table->index(['sales_order_id', 'product_id'], 'sales_order_details_order_product_idx');
            });
        }

        // =====================================================
        // SALES_RETURNS TABLE INDEXES
        // =====================================================
        
        // Index for return_date filtering
        if (!Schema::hasIndex('sales_returns', 'sales_returns_return_date_idx')) {
            Schema::table('sales_returns', function (Blueprint $table) {
                $table->index('return_date', 'sales_returns_return_date_idx');
            });
        }

        // Index for customer_id filtering
        if (!Schema::hasIndex('sales_returns', 'sales_returns_customer_id_idx')) {
            Schema::table('sales_returns', function (Blueprint $table) {
                $table->index('customer_id', 'sales_returns_customer_id_idx');
            });
        }

        // Composite index for customer + date queries
        if (!Schema::hasIndex('sales_returns', 'sales_returns_customer_date_idx')) {
            Schema::table('sales_returns', function (Blueprint $table) {
                $table->index(['customer_id', 'return_date'], 'sales_returns_customer_date_idx');
            });
        }

        // Index for created_by filtering
        if (!Schema::hasIndex('sales_returns', 'sales_returns_created_by_idx')) {
            Schema::table('sales_returns', function (Blueprint $table) {
                $table->index('created_by', 'sales_returns_created_by_idx');
            });
        }

        // =====================================================
        // SALES_RETURN_DETAILS TABLE INDEXES
        // =====================================================
        
        // Index for product_id filtering
        if (!Schema::hasIndex('sales_return_details', 'sales_return_details_product_id_idx')) {
            Schema::table('sales_return_details', function (Blueprint $table) {
                $table->index('product_id', 'sales_return_details_product_id_idx');
            });
        }

        // Composite index for sales_return_id + product_id
        if (!Schema::hasIndex('sales_return_details', 'sales_return_details_return_product_idx')) {
            Schema::table('sales_return_details', function (Blueprint $table) {
                $table->index(['sales_return_id', 'product_id'], 'sales_return_details_return_product_idx');
            });
        }

        // =====================================================
        // BACKUP_CHALLANS TABLE INDEXES
        // =====================================================
        
        // Index for invoice_date filtering
        if (!Schema::hasIndex('backup_challans', 'backup_challans_invoice_date_idx')) {
            Schema::table('backup_challans', function (Blueprint $table) {
                $table->index('invoice_date', 'backup_challans_invoice_date_idx');
            });
        }

        // Index for customer_id filtering
        if (!Schema::hasIndex('backup_challans', 'backup_challans_customer_id_idx')) {
            Schema::table('backup_challans', function (Blueprint $table) {
                $table->index('customer_id', 'backup_challans_customer_id_idx');
            });
        }

        // Composite index for customer + date queries
        if (!Schema::hasIndex('backup_challans', 'backup_challans_customer_date_idx')) {
            Schema::table('backup_challans', function (Blueprint $table) {
                $table->index(['customer_id', 'invoice_date'], 'backup_challans_customer_date_idx');
            });
        }

        // Index for created_by filtering
        if (!Schema::hasIndex('backup_challans', 'backup_challans_created_by_idx')) {
            Schema::table('backup_challans', function (Blueprint $table) {
                $table->index('created_by', 'backup_challans_created_by_idx');
            });
        }

        // Index for type filtering
        if (!Schema::hasIndex('backup_challans', 'backup_challans_type_idx')) {
            Schema::table('backup_challans', function (Blueprint $table) {
                $table->index('type', 'backup_challans_type_idx');
            });
        }

        // =====================================================
        // BACKUP_CHALLAN_DETAILS TABLE INDEXES
        // =====================================================
        
        // Index for product_id filtering
        if (!Schema::hasIndex('backup_challan_details', 'backup_challan_details_product_id_idx')) {
            Schema::table('backup_challan_details', function (Blueprint $table) {
                $table->index('product_id', 'backup_challan_details_product_id_idx');
            });
        }

        // Composite index for backup_challan_id + product_id
        if (!Schema::hasIndex('backup_challan_details', 'backup_challan_details_challan_product_idx')) {
            Schema::table('backup_challan_details', function (Blueprint $table) {
                $table->index(['backup_challan_id', 'product_id'], 'backup_challan_details_challan_product_idx');
            });
        }

        // =====================================================
        // CUSTOMERS TABLE INDEXES (if not already added)
        // =====================================================
        
        // Composite index for status + id (active customers lookup)
        if (!Schema::hasIndex('customers', 'customers_status_id_idx')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->index(['status', 'id'], 'customers_status_id_idx');
            });
        }

        // =====================================================
        // USERS TABLE INDEXES
        // =====================================================
        
        // Index for branch_id filtering (users by branch)
        if (!Schema::hasIndex('users', 'users_branch_id_idx')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('branch_id', 'users_branch_id_idx');
            });
        }

        // =====================================================
        // PRODUCT_CATALOGS TABLE INDEXES
        // =====================================================
        
        // Index for status filtering (active products)
        if (!Schema::hasIndex('product_catalogs', 'product_catalogs_status_idx')) {
            Schema::table('product_catalogs', function (Blueprint $table) {
                $table->index('status', 'product_catalogs_status_idx');
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
            $table->dropIndex('sales_orders_invoice_date_idx');
            $table->dropIndex('sales_orders_customer_date_idx');
            $table->dropIndex('sales_orders_status_idx');
            $table->dropIndex('sales_orders_status_date_idx');
            $table->dropIndex('sales_orders_sales_type_idx');
            $table->dropIndex('sales_orders_created_by_idx');
            $table->dropIndex('sales_orders_sales_order_id_idx');
            $table->dropIndex('sales_orders_customer_status_idx');
        });

        // Sales Order Details
        Schema::table('sales_order_details', function (Blueprint $table) {
            $table->dropIndex('sales_order_details_product_id_idx');
            $table->dropIndex('sales_order_details_order_product_idx');
        });

        // Sales Returns
        Schema::table('sales_returns', function (Blueprint $table) {
            $table->dropIndex('sales_returns_return_date_idx');
            $table->dropIndex('sales_returns_customer_id_idx');
            $table->dropIndex('sales_returns_customer_date_idx');
            $table->dropIndex('sales_returns_created_by_idx');
        });

        // Sales Return Details
        Schema::table('sales_return_details', function (Blueprint $table) {
            $table->dropIndex('sales_return_details_product_id_idx');
            $table->dropIndex('sales_return_details_return_product_idx');
        });

        // Backup Challans
        Schema::table('backup_challans', function (Blueprint $table) {
            $table->dropIndex('backup_challans_invoice_date_idx');
            $table->dropIndex('backup_challans_customer_id_idx');
            $table->dropIndex('backup_challans_customer_date_idx');
            $table->dropIndex('backup_challans_created_by_idx');
            $table->dropIndex('backup_challans_type_idx');
        });

        // Backup Challan Details
        Schema::table('backup_challan_details', function (Blueprint $table) {
            $table->dropIndex('backup_challan_details_product_id_idx');
            $table->dropIndex('backup_challan_details_challan_product_idx');
        });

        // Customers
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_status_id_idx');
        });

        // Users
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_branch_id_idx');
        });

        // Product Catalogs
        Schema::table('product_catalogs', function (Blueprint $table) {
            $table->dropIndex('product_catalogs_status_idx');
        });
    }
};
