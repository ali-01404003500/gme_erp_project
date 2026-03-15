<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds critical indexes to optimize the Shipment Explorer Report
     * to load in under 1 second.
     */
    public function up(): void
    {
        // =====================================================
        // SHIPMENT_VERIFIES TABLE INDEXES
        // =====================================================
        
        // Index for customer_id filtering (most common filter)
        if (!Schema::hasIndex('shipment_verifies', 'shipment_verifies_customer_id_idx')) {
            Schema::table('shipment_verifies', function (Blueprint $table) {
                $table->index('customer_id', 'shipment_verifies_customer_id_idx');
            });
        }

        // Index for courier_id filtering
        if (!Schema::hasIndex('shipment_verifies', 'shipment_verifies_courier_id_idx')) {
            Schema::table('shipment_verifies', function (Blueprint $table) {
                $table->index('courier_id', 'shipment_verifies_courier_id_idx');
            });
        }

        // Index for status filtering
        if (!Schema::hasIndex('shipment_verifies', 'shipment_verifies_status_idx')) {
            Schema::table('shipment_verifies', function (Blueprint $table) {
                $table->index('status', 'shipment_verifies_status_idx');
            });
        }

        // Index for created_by (user) filtering
        if (!Schema::hasIndex('shipment_verifies', 'shipment_verifies_created_by_idx')) {
            Schema::table('shipment_verifies', function (Blueprint $table) {
                $table->index('created_by', 'shipment_verifies_created_by_idx');
            });
        }

        // Index for updated_by filtering
        if (!Schema::hasIndex('shipment_verifies', 'shipment_verifies_updated_by_idx')) {
            Schema::table('shipment_verifies', function (Blueprint $table) {
                $table->index('updated_by', 'shipment_verifies_updated_by_idx');
            });
        }

        // Index for updated_at (update date filtering)
        if (!Schema::hasIndex('shipment_verifies', 'shipment_verifies_updated_at_idx')) {
            Schema::table('shipment_verifies', function (Blueprint $table) {
                $table->index('updated_at', 'shipment_verifies_updated_at_idx');
            });
        }

        // Composite index for customer + courier (common filter combination)
        if (!Schema::hasIndex('shipment_verifies', 'shipment_verifies_customer_courier_idx')) {
            Schema::table('shipment_verifies', function (Blueprint $table) {
                $table->index(['customer_id', 'courier_id'], 'shipment_verifies_customer_courier_idx');
            });
        }

        // Composite index for customer + status
        if (!Schema::hasIndex('shipment_verifies', 'shipment_verifies_customer_status_idx')) {
            Schema::table('shipment_verifies', function (Blueprint $table) {
                $table->index(['customer_id', 'status'], 'shipment_verifies_customer_status_idx');
            });
        }

        // Composite index for status + approved_at (complete shipments)
        // Note: Skip this index - approved_at column doesn't exist in shipment_verifies

        // Index for shipment_id
        if (!Schema::hasIndex('shipment_verifies', 'shipment_verifies_shipment_id_idx')) {
            Schema::table('shipment_verifies', function (Blueprint $table) {
                $table->index('shipment_id', 'shipment_verifies_shipment_id_idx');
            });
        }

        // Index for courier_date
        if (!Schema::hasIndex('shipment_verifies', 'shipment_verifies_courier_date_idx')) {
            Schema::table('shipment_verifies', function (Blueprint $table) {
                $table->index('courier_date', 'shipment_verifies_courier_date_idx');
            });
        }

        // Composite index for source polymorphic relationship
        if (!Schema::hasIndex('shipment_verifies', 'shipment_verifies_source_idx')) {
            Schema::table('shipment_verifies', function (Blueprint $table) {
                $table->index(['source_type', 'source_id'], 'shipment_verifies_source_idx');
            });
        }

        // =====================================================
        // COURIERS TABLE INDEXES
        // =====================================================
        
        // Index for courier_name searches
        if (!Schema::hasIndex('couriers', 'couriers_courier_name_idx')) {
            Schema::table('couriers', function (Blueprint $table) {
                $table->index('courier_name', 'couriers_courier_name_idx');
            });
        }

        // Index for status filtering
        if (!Schema::hasIndex('couriers', 'couriers_status_idx')) {
            Schema::table('couriers', function (Blueprint $table) {
                $table->index('status', 'couriers_status_idx');
            });
        }

        // =====================================================
        // SALES_ORDER_SHIPMENTS TABLE INDEXES
        // =====================================================
        
        // Index for sales_order_id
        if (!Schema::hasIndex('sales_order_shipments', 'sales_order_shipments_sales_order_id_idx')) {
            Schema::table('sales_order_shipments', function (Blueprint $table) {
                $table->index('sales_order_id', 'sales_order_shipments_sales_order_id_idx');
            });
        }

        // Note: condition column doesn't exist in sales_order_shipments table
        // Shipment type filtering is done through related tables

        // =====================================================
        // SALES_ORDERS TABLE INDEXES (if not already added)
        // =====================================================
        
        // Composite index for invoice_date + id (date filtering)
        if (!Schema::hasIndex('sales_orders', 'sales_orders_invoice_date_id_idx')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->index(['invoice_date', 'id'], 'sales_orders_invoice_date_id_idx');
            });
        }

        // =====================================================
        // CUSTOMERS TABLE INDEXES (if not already added)
        // =====================================================
        
        // Composite index for status + id (active customers)
        if (!Schema::hasIndex('customers', 'customers_status_active_idx')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->index(['status', 'id'], 'customers_status_active_idx');
            });
        }

        // =====================================================
        // USERS TABLE INDEXES (if not already added)
        // =====================================================
        
        // Index for branch_id filtering
        if (!Schema::hasIndex('users', 'users_branch_id_lookup_idx')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('branch_id', 'users_branch_id_lookup_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Shipment Verifies
        Schema::table('shipment_verifies', function (Blueprint $table) {
            $table->dropIndex('shipment_verifies_customer_id_idx');
            $table->dropIndex('shipment_verifies_courier_id_idx');
            $table->dropIndex('shipment_verifies_status_idx');
            $table->dropIndex('shipment_verifies_created_by_idx');
            $table->dropIndex('shipment_verifies_updated_by_idx');
            // $table->dropIndex('shipment_verifies_approved_at_idx'); // Not created - column doesn't exist
            $table->dropIndex('shipment_verifies_updated_at_idx');
            $table->dropIndex('shipment_verifies_customer_courier_idx');
            $table->dropIndex('shipment_verifies_customer_status_idx');
            // $table->dropIndex('shipment_verifies_status_approved_idx'); // Not created - column doesn't exist
            $table->dropIndex('shipment_verifies_shipment_id_idx');
            $table->dropIndex('shipment_verifies_courier_date_idx');
            $table->dropIndex('shipment_verifies_source_idx');
        });

        // Couriers
        Schema::table('couriers', function (Blueprint $table) {
            $table->dropIndex('couriers_courier_name_idx');
            $table->dropIndex('couriers_status_idx');
        });

        // Sales Order Shipments
        Schema::table('sales_order_shipments', function (Blueprint $table) {
            $table->dropIndex('sales_order_shipments_sales_order_id_idx');
            // $table->dropIndex('sales_order_shipments_condition_idx'); // Not created - column doesn't exist
        });

        // Sales Orders
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropIndex('sales_orders_invoice_date_id_idx');
        });

        // Customers
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_status_active_idx');
        });

        // Users
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_branch_id_lookup_idx');
        });
    }
};
