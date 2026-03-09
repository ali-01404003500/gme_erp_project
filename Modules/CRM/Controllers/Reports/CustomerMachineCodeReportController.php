<?php

namespace Modules\CRM\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\GeoLocation;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderDetails;
use Modules\Account\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Services\ExportService;
use Carbon\Carbon;

class CustomerMachineCodeReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter data
        $filterData = $this->getFilterData();
        
        // Build optimized customer query with necessary relationships
        $query = Customer::with(['accounts' => function($q) {
            $q->whereIn('account_subsidiary_id', [1005, 2003]); // Receivable and Advance accounts only
        }, 'area'])
        ->where('status', 2); // Active customers only
        
        // Apply filters
        $query = $this->applyFilters($query, $request);
        
        // Get customers
        $customers = $query->get();
        
        // Process customer data with optimized queries
        $reportData = $this->processCustomerDataOptimized($customers, $request);
        
        // Calculate total due balance
        $totalDueBalance = $reportData->sum('due_balance');
        
        // Handle export
        if ($request->filled('export_type')) {
            return $this->exportReport($reportData, $filterData, $totalDueBalance, $request->export_type);
        }
        
        // Paginate results
        $perPage = 50;
        $currentPage = $request->input('page', 1);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $reportData->forPage($currentPage, $perPage),
            $reportData->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        return view('CRM::customer-machine-code.index', [
            'reportData' => $paginatedData,
            'productTypes' => $filterData['productTypes'],
            'divisions' => $filterData['divisions'],
            'districts' => $filterData['districts'],
            'company_info' => $filterData['company_info'],
            'totalDueBalance' => $totalDueBalance
        ]);
    }
    
    /**
     * Apply filters to customer query
     */
    private function applyFilters($query, $request)
    {
        // Division filter
        if ($request->filled('division_id')) {
            $query->whereHas('area', function($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }
        
        // District filter
        if ($request->filled('district_id')) {
            $query->whereHas('area', function($q) use ($request) {
                $q->where('district_id', $request->district_id);
            });
        }
        
        return $query;
    }
    
    /**
     * Process customer data with optimized bulk queries
     */
    private function processCustomerDataOptimized($customers, $request)
    {
        if ($customers->isEmpty()) {
            return collect();
        }
        
        $reportData = collect();
        $productTypeId = $request->input('product_type_id');
        $customerIds = $customers->pluck('id')->toArray();
        
        // Bulk fetch last 6 months sales for all customers
        $bulkSalesData = $this->getBulkLast6MonthsSales($customerIds, $productTypeId);
        
        // Bulk fetch last 3 payments for all customers
        $bulkPaymentsData = $this->getBulkLastPayments($customerIds);
        
        // Bulk fetch account balances
        $accountBalances = $this->getBulkAccountBalances($customerIds);
        
        foreach ($customers as $customer) {
            $customerId = $customer->id;
            
            // Get data from bulk results
            $last6MonthsSales = $bulkSalesData[$customerId] ?? collect();
            $lastPayments = $bulkPaymentsData[$customerId] ?? collect();
            $balances = $accountBalances[$customerId] ?? ['receivable' => 0, 'advance' => 0];
            
            // Calculate net due balance (Receivable - Advance)
            $dueBalance = $balances['receivable'] - $balances['advance'];
            
            // Skip customers with no data (no sales, no payments, and zero due balance)
            if ($last6MonthsSales->count() == 0 && $lastPayments->count() == 0 && $dueBalance == 0) {
                continue;
            }
            
            $reportData->push([
                'customer_id' => $customerId,
                'customer_name' => $customer->company_name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'last_6_months_sales' => $last6MonthsSales,
                'last_payments' => $lastPayments,
                'receivable_balance' => $balances['receivable'],
                'advance_balance' => $balances['advance'],
                'due_balance' => $dueBalance
            ]);
        }
        
        return $reportData;
    }
    
    /**
     * Bulk fetch last 6 months sales for multiple customers
     */
    private function getBulkLast6MonthsSales($customerIds, $productTypeId = null)
    {
        $salesData = [];
        $currentDate = Carbon::now();

        // Initialize empty collections for each customer
        foreach ($customerIds as $customerId) {
            $salesData[$customerId] = collect();
        }

        // Calculate date range once
        $sixMonthsAgo = $currentDate->copy()->subMonths(5)->startOfMonth();

        if ($productTypeId && $productTypeId != 'all') {
            // Single query for all 6 months with product type filter
            $allSales = DB::table('sales_order_details as sod')
                ->join('sales_orders as so', 'sod.sales_order_id', '=', 'so.id')
                ->join('product_catalogs as pc', 'sod.product_id', '=', 'pc.id')
                ->whereIn('so.customer_id', $customerIds)
                ->whereIn('so.status', ['delivered', 'partial'])
                ->whereBetween('so.invoice_date', [$sixMonthsAgo, $currentDate->copy()->endOfMonth()])
                ->where('pc.product_type_id', $productTypeId)
                ->whereNull('so.deleted_at')
                ->select(
                    'so.customer_id',
                    'so.invoice_date',
                    DB::raw('SUM(sod.quantity * sod.price) as total_amount')
                )
                ->groupBy('so.customer_id', 'so.invoice_date')
                ->get();

            // Group by customer and month
            foreach ($allSales as $sale) {
                if ($sale->total_amount > 0) {
                    $monthLabel = Carbon::parse($sale->invoice_date)->format('M-Y');
                    $salesData[$sale->customer_id]->push([
                        'month' => $monthLabel,
                        'amount' => $sale->total_amount
                    ]);
                }
            }
        } else {
            // Single query for all 6 months
            $allSales = DB::table('sales_orders')
                ->whereIn('customer_id', $customerIds)
                ->whereIn('status', ['delivered', 'partial'])
                ->whereBetween('invoice_date', [$sixMonthsAgo, $currentDate->copy()->endOfMonth()])
                ->whereNull('deleted_at')
                ->select('customer_id', 'invoice_date', DB::raw('SUM(net_amount) as total_amount'))
                ->groupBy('customer_id', 'invoice_date')
                ->get();

            // Group by customer and month
            foreach ($allSales as $sale) {
                if ($sale->total_amount > 0) {
                    $monthLabel = Carbon::parse($sale->invoice_date)->format('M-Y');
                    $salesData[$sale->customer_id]->push([
                        'month' => $monthLabel,
                        'amount' => $sale->total_amount
                    ]);
                }
            }
        }

        return $salesData;
    }
    
    /**
     * Bulk fetch last 3 payments for multiple customers
     */
    private function getBulkLastPayments($customerIds)
    {
        $paymentsData = [];

        // Initialize empty collections
        foreach ($customerIds as $customerId) {
            $paymentsData[$customerId] = collect();
        }

        // Get account IDs for customers with receivable and advance accounts
        $accountIds = DB::table('accounts')
            ->where('accountable_type', 'Modules\\CRM\\Models\\Customer\\Customer')
            ->whereIn('accountable_id', $customerIds)
            ->whereIn('account_subsidiary_id', [1005, 2003]) // Receivable and Advance
            ->whereNull('deleted_at')
            ->pluck('id', 'accountable_id');

        if ($accountIds->isEmpty()) {
            return $paymentsData;
        }

        // Get last 3 credit transactions per account using a subquery approach
        $allPayments = DB::table('transactions')
            ->whereIn('account_id', $accountIds->values()->toArray())
            ->where('balance_type', 'credit')
            ->whereNull('deleted_at')
            ->select('account_id', 'created_at', 'amount')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by account_id and limit to 3 per account
        $payments = [];
        foreach ($allPayments as $payment) {
            if (!isset($payments[$payment->account_id])) {
                $payments[$payment->account_id] = collect();
            }
            if ($payments[$payment->account_id]->count() < 3) {
                $payments[$payment->account_id]->push($payment);
            }
        }

        // Map payments to customers
        foreach ($accountIds as $customerId => $accountId) {
            if (isset($payments[$accountId]) && $payments[$accountId]->count() > 0) {
                $customerPayments = $payments[$accountId]->map(function($transaction) {
                    return [
                        'month' => Carbon::parse($transaction->created_at)->format('M-Y'),
                        'amount' => $transaction->amount
                    ];
                });

                $paymentsData[$customerId] = $customerPayments;
            }
        }

        return $paymentsData;
    }
    
    /**
     * Bulk fetch account balances (Receivable and Advance) for multiple customers
     */
    private function getBulkAccountBalances($customerIds)
    {
        $balances = [];

        // Initialize balances
        foreach ($customerIds as $customerId) {
            $balances[$customerId] = [
                'receivable' => 0,
                'advance' => 0
            ];
        }

        // Get all accounts with their balances in a single joined query
        $accountBalances = DB::table('accounts')
            ->leftJoin('transactions', function($join) {
                $join->on('accounts.id', '=', 'transactions.account_id')
                     ->whereNull('transactions.deleted_at');
            })
            ->where('accounts.accountable_type', 'Modules\\CRM\\Models\\Customer\\Customer')
            ->whereIn('accounts.accountable_id', $customerIds)
            ->whereIn('accounts.account_subsidiary_id', [1005, 2003])
            ->whereNull('accounts.deleted_at')
            ->select(
                'accounts.accountable_id as customer_id',
                'accounts.account_subsidiary_id',
                'accounts.id as account_id',
                DB::raw('COALESCE(SUM(transactions.amount), 0) as balance')
            )
            ->groupBy('accounts.accountable_id', 'accounts.account_subsidiary_id', 'accounts.id')
            ->get();

        // Map balances to customers
        foreach ($accountBalances as $account) {
            if ($account->account_subsidiary_id == 1005) {
                $balances[$account->customer_id]['receivable'] = (float)$account->balance;
            } elseif ($account->account_subsidiary_id == 2003) {
                $balances[$account->customer_id]['advance'] = (float)$account->balance;
            }
        }

        return $balances;
    }
    
    /**
     * Get filter dropdown data
     */
    private function getFilterData()
    {
        return [
            'productTypes' => ProductCatalog::select('product_type_id')
                ->with('productType')
                ->whereNotNull('product_type_id')
                ->get()
                ->pluck('productType')
                ->unique('id')
                ->filter(), // Remove null values
            'divisions' => GeoLocation::where('type', 'Division')->get(),
            'districts' => GeoLocation::where('type', 'District')->get(),
            'company_info' => CompanyInfo::first()
        ];
    }
    
    /**
     * Export report
     */
    private function exportReport($reportData, $filterData, $totalDueBalance, $exportType)
    {
        $data = array_merge([
            'reportData' => $reportData,
            'totalDueBalance' => $totalDueBalance
        ], $filterData);
        
        $filename = 'Customer_Machine_Code_Report_' . now()->format('Y_m_d_His');
        
        return (new ExportService())->exportData(
            $data,
            'CRM::customer-machine-code.export.',
            $filename,
            $exportType
        );
    }
}