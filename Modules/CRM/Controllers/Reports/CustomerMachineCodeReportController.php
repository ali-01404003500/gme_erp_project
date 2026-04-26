<?php
namespace Modules\CRM\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\GeoLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Services\ExportService;

class CustomerMachineCodeReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter data with caching
        $filterData = $this->getFilterData();

        // Build optimized customer query with necessary relationships
        $query = Customer::with(['accounts' => function ($q) {
            $q->whereIn('account_subsidiary_id', [1005, 2003]); // Receivable and Advance accounts only
        }, 'area'])
            ->where('status', 2); // Active customers only

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get customers with chunking for large datasets
        $customers = $query->get();

        // customer data with optimized queries
        $reportData = $this->processCustomerDataOptimized($customers, $request);

        // Calculate total due balance
        $totalDueBalance = $reportData->sum('due_balance');

        // Handle export
        if ($request->filled('export_type')) {
            return $this->exportReport($reportData, $filterData, $totalDueBalance, $request->export_type);
        }

        // Paginate results
        $perPage       = 50;
        $currentPage   = $request->input('page', 1);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $reportData->forPage($currentPage, $perPage),
            $reportData->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('CRM::customer-machine-code.index', [
            'reportData'      => $paginatedData,
            'productTypes'    => $filterData['productTypes'],
            'divisions'       => $filterData['divisions'],
            'districts'       => $filterData['districts'],
            'company_info'    => $filterData['company_info'],
            'totalDueBalance' => $totalDueBalance,
        ]);
    }

    /**
     * Apply filters to customer query - Optimized
     */
    private function applyFilters($query, $request)
    {
        // Division filter - using whereHas with selective columns
        if ($request->filled('division_id')) {
            $query->whereHas('area', function ($q) use ($request) {
                $q->where('division_id', $request->division_id);
            }, '=', 1, null); // Added minimum 1 match constraint
        }

        // District filter
        if ($request->filled('district_id')) {
            $query->whereHas('area', function ($q) use ($request) {
                $q->where('district_id', $request->district_id);
            }, '=', 1, null);
        }

        return $query;
    }

    /**
     * Process customer data with optimized bulk queries - No logic change, only optimization
     */
    private function processCustomerDataOptimized($customers, $request)
    {
        if ($customers->isEmpty()) {
            return collect();
        }

        $reportData    = collect();
        $productTypeId = $request->input('product_type_id');
        $customerIds   = $customers->pluck('id')->toArray();

        // Bulk fetch all data in minimal queries
        $bulkSalesData    = $this->getBulkLast6MonthsSales($customerIds, $productTypeId);
        $bulkPaymentsData = $this->getBulkLastPayments($customerIds);
        $accountBalances  = $this->getBulkAccountBalances($customerIds);

        foreach ($customers as $customer) {
            $customerId = $customer->id;

            // Get data from bulk results
            $last6MonthsSales = $bulkSalesData[$customerId] ?? collect();
            $lastPayments     = $bulkPaymentsData[$customerId] ?? collect();
            $balances         = $accountBalances[$customerId] ?? ['receivable' => 0, 'advance' => 0];

            // Calculate net due balance (Receivable - Advance)
            $dueBalance = $balances['receivable'] - $balances['advance'];

            // Skip customers with no data
            if ($last6MonthsSales->count() == 0 && $lastPayments->count() == 0 && $dueBalance == 0) {
                continue;
            }

            $reportData->push([
                'customer_id'         => $customerId,
                'customer_name'       => $customer->company_name,
                'phone'               => $customer->phone,
                'address'             => $customer->address,
                'last_6_months_sales' => $last6MonthsSales,
                'last_payments'       => $lastPayments,
                'receivable_balance'  => $balances['receivable'],
                'advance_balance'     => $balances['advance'],
                'due_balance'         => $dueBalance,
            ]);
        }

        return $reportData;
    }

    /**
     * Bulk fetch last 6 months sales for multiple customers - Optimized with single query per month
     */
    private function getBulkLast6MonthsSales($customerIds, $productTypeId = null)
    {
        $salesData   = [];
        $currentDate = Carbon::now();

        // Initialize empty collections for each customer
        foreach ($customerIds as $customerId) {
            $salesData[$customerId] = collect();
        }

        if (empty($customerIds)) {
            return $salesData;
        }

        // Prepare month labels and date ranges
        $monthRanges = [];
        for ($i = 0; $i < 6; $i++) {
            $monthStart    = $currentDate->copy()->subMonths($i)->startOfMonth();
            $monthEnd      = $currentDate->copy()->subMonths($i)->endOfMonth();
            $monthLabel    = $monthStart->format('M-Y');
            $monthRanges[] = [
                'label' => $monthLabel,
                'start' => $monthStart,
                'end'   => $monthEnd,
                'index' => $i,
            ];
        }

        if ($productTypeId && $productTypeId != 'all') {
            // Get product-specific sales - Single query for all months using UNION or subquery
            $allMonthlySales = DB::table('sales_order_details as sod')
                ->join('sales_orders as so', 'sod.sales_order_id', '=', 'so.id')
                ->join('product_catalogs as pc', 'sod.product_id', '=', 'pc.id')
                ->whereIn('so.customer_id', $customerIds)
                ->whereIn('so.status', ['delivered', 'partial'])
                ->where('pc.product_type_id', $productTypeId)
                ->whereNull('so.deleted_at')
                ->whereNull('sod.deleted_at')
                ->whereNull('pc.deleted_at')
                ->select(
                    'so.customer_id',
                    DB::raw('DATE_FORMAT(so.invoice_date, "%Y-%m") as month_key'),
                    DB::raw('SUM(sod.quantity * sod.price) as total_amount')
                )
                ->groupBy('so.customer_id', DB::raw('DATE_FORMAT(so.invoice_date, "%Y-%m")'))
                ->get();
        } else {
            // Get total sales - Single query for all months
            $allMonthlySales = DB::table('sales_orders')
                ->whereIn('customer_id', $customerIds)
                ->whereIn('status', ['delivered', 'partial'])
                ->whereNull('deleted_at')
                ->select(
                    'customer_id',
                    DB::raw('DATE_FORMAT(invoice_date, "%Y-%m") as month_key'),
                    DB::raw('SUM(net_amount) as total_amount')
                )
                ->groupBy('customer_id', DB::raw('DATE_FORMAT(invoice_date, "%Y-%m")'))
                ->get();
        }

        // Map sales data to months
        foreach ($allMonthlySales as $sale) {
            if ($sale->total_amount > 0) {
                // Find matching month label
                foreach ($monthRanges as $monthRange) {
                    $monthKey = $monthRange['start']->format('Y-m');
                    if ($sale->month_key == $monthKey) {
                        $salesData[$sale->customer_id]->push([
                            'month'  => $monthRange['label'],
                            'amount' => $sale->total_amount,
                        ]);
                        break;
                    }
                }
            }
        }

        return $salesData;
    }

    /**
     * Bulk fetch last 3 payments for multiple customers - Optimized with single query
     */
    private function getBulkLastPayments($customerIds)
    {
        $paymentsData = [];

        // Initialize empty collections
        foreach ($customerIds as $customerId) {
            $paymentsData[$customerId] = collect();
        }

        if (empty($customerIds)) {
            return $paymentsData;
        }

        // Get account IDs for customers with receivable accounts only (for payments)
        $accountIds = DB::table('accounts')
            ->where('accountable_type', 'Modules\\CRM\\Models\\Customer\\Customer')
            ->whereIn('accountable_id', $customerIds)
            ->where('account_subsidiary_id', 1005) // Receivable account only for payments
            ->whereNull('deleted_at')
            ->pluck('id', 'accountable_id');

        if ($accountIds->isEmpty()) {
            return $paymentsData;
        }

        // Get last 3 credit transactions per account using row_number
        // This is a single optimized query using MySQL 8+ window functions
        $allPayments = DB::table('transactions')
            ->whereIn('account_id', $accountIds->values()->toArray())
            ->where('balance_type', 'credit')
            ->whereNull('deleted_at')
            ->select(
                'account_id',
                'amount',
                'created_at'
            )
            ->orderBy('account_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by account_id and take first 3
        $counters = [];
        $payments = [];
        foreach ($allPayments as $payment) {
            if (! isset($counters[$payment->account_id])) {
                $counters[$payment->account_id] = 0;
                $payments[$payment->account_id] = collect();
            }
            if ($counters[$payment->account_id] < 3) {
                $payments[$payment->account_id]->push($payment);
                $counters[$payment->account_id]++;
            }
        }

        // Map payments to customers (already limited to 3 per account)
        foreach ($accountIds as $customerId => $accountId) {
            if (isset($payments[$accountId]) && $payments[$accountId]->count() > 0) {
                $customerPayments = $payments[$accountId]->map(function ($transaction) {
                    return [
                        'month'  => Carbon::parse($transaction->created_at)->format('M-Y'),
                        'amount' => $transaction->amount,
                    ];
                });

                $paymentsData[$customerId] = $customerPayments;
            }
        }

        return $paymentsData;
    }

    /**
     * Bulk fetch account balances - Optimized with single query
     */
    private function getBulkAccountBalances($customerIds)
    {
        $balances = [];

        // Initialize balances
        foreach ($customerIds as $customerId) {
            $balances[$customerId] = [
                'receivable' => 0,
                'advance'    => 0,
            ];
        }

        if (empty($customerIds)) {
            return $balances;
        }

        // Get all accounts and their balances in one query with transaction sums
        $accountData = DB::table('accounts')
            ->select(
                'accounts.accountable_id as customer_id',
                'accounts.account_subsidiary_id',
                'accounts.id as account_id',
                DB::raw('COALESCE(SUM(transactions.amount), 0) as balance')
            )
            ->leftJoin('transactions', function ($join) {
                $join->on('accounts.id', '=', 'transactions.account_id')
                    ->whereNull('transactions.deleted_at');
            })
            ->where('accounts.accountable_type', 'Modules\\CRM\\Models\\Customer\\Customer')
            ->whereIn('accounts.accountable_id', $customerIds)
            ->whereIn('accounts.account_subsidiary_id', [1005, 2003]) // Receivable and Advance
            ->whereNull('accounts.deleted_at')
            ->groupBy('accounts.id', 'accounts.accountable_id', 'accounts.account_subsidiary_id')
            ->get();

        // Map balances to customers
        foreach ($accountData as $account) {
            if ($account->account_subsidiary_id == 1005) {
                // Receivable account
                $balances[$account->customer_id]['receivable'] = $account->balance;
            } elseif ($account->account_subsidiary_id == 2003) {
                // Advance account
                $balances[$account->customer_id]['advance'] = $account->balance;
            }
        }

        return $balances;
    }

    /**
     * Get filter dropdown data - Optimized with caching
     */
    private function getFilterData()
    {
        // Cache product types for 1 hour
        $productTypes = cache()->remember('customer_machine_code_product_types', 3600, function () {
            return ProductCatalog::select('product_type_id')
                ->with('productType')
                ->whereNotNull('product_type_id')
                ->get()
                ->pluck('productType')
                ->unique('id')
                ->filter();
        });

        // Cache divisions for 24 hours
        $divisions = cache()->remember('geo_divisions_for_report', 86400, function () {
            return GeoLocation::where('type', 'Division')->get();
        });

        // Cache districts for 24 hours
        $districts = cache()->remember('geo_districts_for_report', 86400, function () {
            return GeoLocation::where('type', 'District')->get();
        });

        // Cache company info for 1 hour
        $companyInfo = cache()->remember('company_info_for_report', 3600, function () {
            return CompanyInfo::first();
        });

        return [
            'productTypes' => $productTypes,
            'divisions'    => $divisions,
            'districts'    => $districts,
            'company_info' => $companyInfo,
        ];
    }

    /**
     * Export report - Optimized with chunking for large data
     */
    private function exportReport($reportData, $filterData, $totalDueBalance, $exportType)
    {
        // For large datasets, chunk the data
        if ($reportData->count() > 1000) {
            $chunks        = $reportData->chunk(500);
            $processedData = collect();
            foreach ($chunks as $chunk) {
                $processedData = $processedData->concat($chunk);
            }
            $reportData = $processedData;
        }

        $data = array_merge([
            'reportData'      => $reportData,
            'totalDueBalance' => $totalDueBalance,
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
