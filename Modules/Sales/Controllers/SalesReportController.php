<?php

namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use App\Models\User;
use Modules\Inventory\Services\ExportService;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesReturn;
use Modules\Sales\Models\BackupChallan;
use Modules\Sales\Models\FreeSalesInvoice;
use Modules\Sales\Models\OtpVerification;
use Modules\CRM\Models\Customer\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        // Combine data for unified report
        $reportData = collect();
        
        // Determine what types to include based on invoice_type and sales_type filters
        $includeTypes = $this->getIncludeTypes($request);
        
        // Add sales orders if needed
        if ($includeTypes['sales_orders']) {
            $query = SalesOrder::with([
                'customer',
                'customer.account',
                'salesOrderDetails.product',
                'createdBy',
                'approvedBy',
                'payments',
                'delivery',
                'shipment',
                'transactions',
                'reference',
                'otpVerifications'
            ]);

            $query = $this->applySalesOrderFilters($query, $request);
            $salesOrders = $query->orderBy('created_at', 'desc')->get();

            foreach ($salesOrders as $order) {
                // Apply product filter if needed
                if ($request->filled('product_id')) {
                    $hasProduct = $order->salesOrderDetails->contains('product_id', $request->product_id);
                    if (!$hasProduct) continue;
                }
                
                // Calculate customer balance
                $customerBalance = $this->calculateCustomerBalance($order->customer);
                
                // Get commitment date from OTP verification
                $commitmentDate = $order->otpVerifications->where('title', 'Credit Limit Exceeded')->first()->additional_data['payment_date'] ?? null;
                // dd( $commitmentDate );
                
                $reportData->push([
                    'invoice_type' => $this->getInvoiceType($order),
                    'invoice_status' => $this->getInvoiceStatus($order),
                    'data' => $order,
                    'date' => $order->invoice_date,
                    'customer_balance' => $customerBalance,
                    'commitment_date' => $commitmentDate
                ]);
            }
        }

        // Add returns if needed
        if ($includeTypes['sales_return']) {
            $returnQuery = SalesReturn::with([
                'customer',
                'customer.account',
                'salesOrder',
                'salesReturnDetails.product',
                'createdBy'
            ]);

            $returnQuery = $this->applyReturnFilters($returnQuery, $request);
            $salesReturns = $returnQuery->orderBy('created_at', 'desc')->get();

            foreach ($salesReturns as $return) {
                if ($request->filled('product_id')) {
                    $hasProduct = $return->salesReturnDetails->contains('product_id', $request->product_id);
                    if (!$hasProduct) continue;
                }
                
                // Calculate customer balance
                $customerBalance = $this->calculateCustomerBalance($return->customer);
                
                $reportData->push([
                    'invoice_type' => 'Sales Return',
                    'invoice_status' => 'Return',
                    'data' => $return,
                    'date' => $return->return_date,
                    'customer_balance' => $customerBalance,
                    'commitment_date' => null
                ]);
            }
        } else {
            $salesReturns = collect();
        }

        // Add backup challans if needed
        if ($includeTypes['backup_challan']) {
            $challanQuery = BackupChallan::with([
                'customer',
                'customer.account',
                'backupChallanDetails.product',
                'createdBy'
            ]);

            $challanQuery = $this->applyChallanFilters($challanQuery, $request);
            $backupChallans = $challanQuery->orderBy('created_at', 'desc')->get();

            foreach ($backupChallans as $challan) {
                if ($request->filled('product_id')) {
                    $hasProduct = $challan->backupChallanDetails->contains('product_id', $request->product_id);
                    if (!$hasProduct) continue;
                }
                
                // Calculate customer balance
                $customerBalance = $this->calculateCustomerBalance($challan->customer);
                
                $reportData->push([
                    'invoice_type' => 'Backup/Challan',
                    'invoice_status' => $challan->status ?? 'Approved',
                    'data' => $challan,
                    'date' => $challan->invoice_date,
                    'customer_balance' => $customerBalance,
                    'commitment_date' => null
                ]);
            }
        } else {
            $backupChallans = collect();
        }

        // Sort by date descending
        $reportData = $reportData->sortByDesc('date')->values();

        // Get all sales orders for dropdown (unfiltered)
        $allSalesOrders = SalesOrder::orderBy('created_at', 'desc')->limit(100)->get();

        // Handle export (before pagination)
        if ($request->filled('export_type')) {
            $data = [
                'reportData' => $reportData,
                'salesOrders' => $allSalesOrders,
                'salesReturns' => $salesReturns,
                'customers' => Customer::activeCustomers()->get(),
                'branches' => Branch::all(),
                'products' => ProductCatalog::where('status', 'active')->get(),
                'users' => User::whereDoesntHave('roles', function ($query) {
                    $query->where('slug', 'customer');
                })->get(),
                'company_info' => CompanyInfo::first(),
            ];
            
            $filename = 'Sales_Report_' . now()->format('Y_m_d_His');
            
            return (new ExportService())->exportData(
                $data,
                'Sales::reports.export.',
                $filename,
                $request->export_type
            );
        }

        // Paginate the report data
        $perPage = 50;
        $currentPage = request()->input('page', 1);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $reportData->forPage($currentPage, $perPage),
            $reportData->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Prepare view data
        $data = [
            'reportData' => $paginatedData,
            'salesOrders' => $allSalesOrders,
            'salesReturns' => $salesReturns,
            'customers' => Customer::activeCustomers()->get(),
            'branches' => Branch::all(),
            'products' => ProductCatalog::where('status', 'active')->get(),
            'users' => User::whereDoesntHave('roles', function ($query) {
                $query->where('slug', 'customer');
            })->get(),
            'company_info' => CompanyInfo::first(),
        ];

        return view('Sales::reports.sales-reports', $data);
    }

    /**
     * Get commitment date from OTP verification for Credit Limit Exceeded
     */
    private function getCommitmentDate($order)
    {
        try {
            // Get the approved OTP verification with title 'Credit Limit Exceeded'
            $otpVerification = $order->otpVerifications()
                ->where('title', 'Credit Limit Exceeded')
                ->latest()
                ->first();

            if (!$otpVerification) {
                return null;
            }
            ;

            // Decode additional_data to get payment_date
            $additionalData = $order->otpVerifications()
                ->where('title', 'Credit Limit Exceeded')
                ->latest()
                ->first()->first()->additional_data;
            
            if (isset($additionalData['payment_date']) && !empty($additionalData['payment_date'])) {
                return $additionalData['payment_date'];
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Error getting commitment date: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Calculate customer balance
     */
    private function calculateCustomerBalance($customer)
    {
        if (!$customer) {
            return 0;
        }

        try {
            // Method 1: Using account balance (if available)
            if (method_exists($customer, 'getAccount')) {
                $account = $customer->getAccount();
                if ($account && isset($account->balance)) {
                    return $account->balance;
                }
            }

            // Method 2: Calculate from sales orders and payments
            $totalSales = SalesOrder::where('customer_id', $customer->id)
                ->whereIn('status', ['delivered', 'partial'])
                ->sum('net_amount');
            
            // Get total payments made by customer
            $totalPaid = $customer->payments()->sum('amount');
            
            // Balance = Sales - Payments (positive means customer owes money)
            $balance = $totalSales - $totalPaid;
            
            return $balance;

        } catch (\Exception $e) {
            Log::error('Error calculating customer balance: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get the Invoice Type based on sales_type
     */
    private function getInvoiceType($order)
    {
        if ($order->sales_type == 'partial_sales') {
            return 'Partial Sales';
        }
        
        if ($order->sales_type == 'free_sales') {
            return 'Free Sales';
        }
        
        return 'General Sales';
    }

    /**
     * Get the Invoice Status based on status field
     */
    private function getInvoiceStatus($order)
    {
        switch ($order->status) {
            case 'delivered':
                return 'Delivered';
            case 'pending':
                return 'Pending';
            case 'approved':
                return 'Undelivered';
            case 'cancelled':
                return 'Cancelled';
            default:
                return ucfirst($order->status);
        }
    }

    /**
     * Determine which record types to include based on invoice_type and sales_type filters
     */
    private function getIncludeTypes($request)
    {
        // If sales_type is provided, exclude sales_return and backup_challan
        if ($request->filled('sales_type')) {
            $salesType = $request->input('sales_type');
            
            // Map sales_type to included types
            $typeMap = [
                'general_sales' => [
                    'sales_orders' => true,
                    'sales_return' => false,
                    'backup_challan' => false,
                ],
                'partial_sales' => [
                    'sales_orders' => true,
                    'sales_return' => false,
                    'backup_challan' => false,
                ],
                'free_sales' => [
                    'sales_orders' => true,
                    'sales_return' => false,
                    'backup_challan' => false,
                ],
            ];
            
            return $typeMap[$salesType] ?? [
                'sales_orders' => true,
                'sales_return' => false,
                'backup_challan' => false,
            ];
        }
        
        // If no sales_type, use invoice_type as before
        $invoiceType = $request->input('invoice_type');
        
        // If no invoice type filter, include everything
        if (!$invoiceType) {
            return [
                'sales_orders' => true,
                'sales_return' => true,
                'backup_challan' => true,
            ];
        }
        
        // Map invoice types to what should be included
        $typeMap = [
            'delivered' => [
                'sales_orders' => true,
                'sales_return' => false,
                'backup_challan' => false,
            ],
            'undelivered' => [
                'sales_orders' => true,
                'sales_return' => false,
                'backup_challan' => false,
            ],
            'pending' => [
                'sales_orders' => true,
                'sales_return' => false,
                'backup_challan' => false,
            ],
            'partial_sales' => [
                'sales_orders' => true,
                'sales_return' => false,
                'backup_challan' => false,
            ],
            'free_sales' => [
                'sales_orders' => true,
                'sales_return' => false,
                'backup_challan' => false,
            ],
            'sales_return' => [
                'sales_orders' => false,
                'sales_return' => true,
                'backup_challan' => false,
            ],
            'backup_challan' => [
                'sales_orders' => false,
                'sales_return' => false,
                'backup_challan' => true,
            ],
        ];
        
        return $typeMap[$invoiceType] ?? [
            'sales_orders' => true,
            'sales_return' => true,
            'backup_challan' => true,
        ];
    }

    private function applySalesOrderFilters($query, $request)
    {
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('branch_id')) {
            $query->whereHas('createdBy', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        if ($request->filled('invoice_id')) {
            $query->where('sales_order_id', 'LIKE', '%' . $request->invoice_id . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('created_by', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply invoice_type filter to sales orders
        if ($request->filled('invoice_type')) {
            switch ($request->invoice_type) {
                case 'delivered':
                    $query->where('status', 'delivered');
                    break;
                case 'undelivered':
                    $query->where('status', '=', 'approved')->where('sales_type', '=', 'general_sales');
                    break;
                case 'pending':
                    $query->where('status', 'pending');
                    break;
                case 'partial_sales':
                    $query->where('sales_type', 'partial_sales');
                    break;
                case 'free_sales':
                    $query->where('sales_type', 'free_sales');
                    break;
            }
        }

        // Apply sales_type filter
        if ($request->filled('sales_type')) {
            $query->where('sales_type', $request->sales_type);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('invoice_date', [$request->from, $request->to]);
        } elseif ($request->filled('from')) {
            $query->where('invoice_date', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->where('invoice_date', '<=', $request->to);
        }

        if ($request->filled('product_id')) {
            $query->whereHas('salesOrderDetails', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('net_amount', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('net_amount', '<=', $request->max_price);
        }

        return $query;
    }

    private function applyReturnFilters($query, $request)
    {
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

         if ($request->filled('branch_id')) {
            $query->whereHas('createdBy', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        if ($request->filled('user_id')) {
            $query->where('created_by', $request->user_id);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('return_date', [$request->from, $request->to]);
        } elseif ($request->filled('from')) {
            $query->where('return_date', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->where('return_date', '<=', $request->to);
        }

        if ($request->filled('product_id')) {
            $query->whereHas('salesReturnDetails', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        return $query;
    }

    private function applyChallanFilters($query, $request)
    {
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

         if ($request->filled('branch_id')) {
            $query->whereHas('createdBy', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        if ($request->filled('user_id')) {
            $query->where('created_by', $request->user_id);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('invoice_date', [$request->from, $request->to]);
        } elseif ($request->filled('from')) {
            $query->where('invoice_date', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->where('invoice_date', '<=', $request->to);
        }

        if ($request->filled('product_id')) {
            $query->whereHas('backupChallanDetails', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        return $query;
    }
}