<?php
namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Account\Models\Account;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Services\ExportService;
use Modules\Sales\Models\BackupChallan;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesReturn;

class SalesReportController extends Controller
{
                                         // -------------------------------------------------------------------------
                                         // CACHE TTL CONSTANTS
                                         // -------------------------------------------------------------------------
                                         // Optimized for 1-second load time with near-real-time data
    private const REPORT_TTL   = 60;     // 1 min - report data
    private const DROPDOWN_TTL = 3_600;  // 1 hr - dropdown data
    private const CUSTOMER_TTL = 3_600;  // 1 hr - customer list
    private const PRODUCT_TTL  = 3_600;  // 1 hr - product list
    private const USER_TTL     = 3_600;  // 1 hr - user list
    private const BRANCH_TTL   = 86_400; // 24 hrs - branch list (static)

    // -------------------------------------------------------------------------
    // PAGINATION
    // -------------------------------------------------------------------------
    private const PER_PAGE = 50;

    public function index(Request $request)
    {
        $includeTypes = $this->getIncludeTypes($request);

        // Handle export - load all data
        if ($request->filled('export_type')) {
            $reportData = Cache::remember('sales_report_export_all', self::REPORT_TTL, function () use ($request, $includeTypes) {
                return $this->buildReportData($request, $includeTypes);
            });
            $salesReturns   = $reportData->filter(fn($item) => $item['invoice_type'] === 'Sales Return');
            $backupChallans = $reportData->filter(fn($item) => $item['invoice_type'] === 'Backup/Challan');
            return $this->exportReport($request, $reportData, $salesReturns, $backupChallans);
        }

        // For normal view, load data and paginate
        $reportData = $this->buildReportData($request, $includeTypes);

        // Paginate the combined collection
        $currentPage   = $request->input('page', 1);
        $perPage       = $request->input('per_page', self::PER_PAGE);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $reportData->forPage($currentPage, $perPage),
            $reportData->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('Sales::reports.sales-reports', $this->getViewData($paginatedData, collect(), collect()));
    }

    /**
     * Generate unique cache key based on request filters
     */
    private function getReportCacheKey(Request $request, array $includeTypes): string
    {
        $filters = [
            'includeTypes' => $includeTypes,
            'customer_id'  => $request->customer_id,
            'invoice_id'   => $request->invoice_id,
            'invoice_type' => $request->invoice_type,
            'sales_type'   => $request->sales_type,
            'branch_id'    => $request->branch_id,
            'user_id'      => $request->user_id,
            'product_id'   => $request->product_id,
            'from'         => $request->from,
            'to'           => $request->to,
            'status'       => $request->status,
            'min_price'    => $request->min_price,
            'max_price'    => $request->max_price,
        ];

        return 'sales_report_' . md5(serialize($filters));
    }

    /**
     * Build consolidated report data from all sources
     */
    private function buildReportData(Request $request, array $includeTypes)
    {
        $reportData = collect();

        // Build sales orders - limit for performance
        if ($includeTypes['sales_orders']) {
            $salesOrders = $this->getFilteredSalesOrders($request);

            foreach ($salesOrders as $order) {
                $reportData->push([
                    'invoice_type'     => $this->getInvoiceType($order),
                    'invoice_status'   => $this->getInvoiceStatus($order),
                    'data'             => $order,
                    'date'             => $order->invoice_date,
                    'customer_balance' => $order->customer_account_balance ?? 0,
                    'commitment_date'  => $order->credit_limit_payment_date ?? null,
                ]);
            }
        }

        // Build sales returns - limit for performance
        if ($includeTypes['sales_return']) {
            $salesReturns = $this->getFilteredSalesReturns($request);

            foreach ($salesReturns as $return) {
                $reportData->push([
                    'invoice_type'     => 'Sales Return',
                    'invoice_status'   => 'Return',
                    'data'             => $return,
                    'date'             => $return->return_date,
                    'customer_balance' => $return->customer_account_balance ?? 0,
                    'commitment_date'  => null,
                ]);
            }
        }

        // Build backup challans - limit for performance
        if ($includeTypes['backup_challan']) {
            $backupChallans = $this->getFilteredBackupChallans($request);

            foreach ($backupChallans as $challan) {
                $reportData->push([
                    'invoice_type'     => 'Backup/Challan',
                    'invoice_status'   => $challan->status ?? 'Approved',
                    'data'             => $challan,
                    'date'             => $challan->invoice_date,
                    'customer_balance' => $challan->customer_account_balance ?? 0,
                    'commitment_date'  => null,
                ]);
            }
        }

        // Sort by date descending
        return $reportData->sortByDesc('date')->values();
    }

    /**
     * Get filtered sales orders with optimized queries
     */
    private function getFilteredSalesOrders(Request $request)
    {
        $query = SalesOrder::with([
            'customer',
            'salesOrderDetails.product',
            'createdBy.branch',
            'approvedBy',
        ]);

        $query = $this->applySalesOrderFilters($query, $request);

        // Eager load only credit limit OTP verifications - optimized without filesort
        $query->with(['otpVerifications' => function ($q) {
            $q->where('title', 'Credit Limit Exceeded')
                ->orderBy('id', 'desc') // Use indexed id column instead of created_at
                ->limit(5);             // Limit to reduce memory usage
        }]);

        // Use simplePaginate for better performance (no OFFSET/COUNT query)
        $perPage = $request->get('per_page', 50);
        $orders  = $query->orderBy('created_at', 'desc')->simplePaginate($perPage);

        // Batch load customer account balances to avoid N+1
        $customerIds      = $orders->pluck('customer_id')->unique()->filter()->toArray();
        $customerBalances = $this->getCustomerBalancesInBulk($customerIds);

        // Enrich with account balance and commitment date
        $orders->getCollection()->transform(function ($order) use ($customerBalances) {
            $order->customer_account_balance = $customerBalances[$order->customer_id] ?? 0;

            $otp                              = $order->otpVerifications->first();
            $order->credit_limit_payment_date = $otp?->additional_data['payment_date'] ?? null;

            return $order;
        });

        return $orders;
    }

    /**
     * Get filtered sales returns with optimized queries
     */
    private function getFilteredSalesReturns(Request $request)
    {
        $query = SalesReturn::with([
            'customer',
            'salesOrder',
            'salesReturnDetails.product',
            'createdBy.branch',
        ]);

        $query = $this->applyReturnFilters($query, $request);

        // Limit results for faster loading
        $perPage = $request->get('per_page', 50);
        $returns = $query->orderBy('created_at', 'desc')->simplePaginate($perPage);

        // Batch load customer account balances
        $customerIds      = $returns->pluck('customer_id')->unique()->filter()->toArray();
        $customerBalances = $this->getCustomerBalancesInBulk($customerIds);

        $returns->getCollection()->transform(function ($return) use ($customerBalances) {
            $return->customer_account_balance = $customerBalances[$return->customer_id] ?? 0;
            return $return;
        });

        return $returns;
    }

    /**
     * Get filtered backup challans with optimized queries
     */
    private function getFilteredBackupChallans(Request $request)
    {
        $query = BackupChallan::with([
            'customer',
            'backupChallanDetails.product',
            'createdBy.branch',
        ]);

        $query = $this->applyChallanFilters($query, $request);

        // Limit results for faster loading
        $perPage  = $request->get('per_page', 50);
        $challans = $query->orderBy('created_at', 'desc')->simplePaginate($perPage);

        // Batch load customer account balances
        $customerIds      = $challans->pluck('customer_id')->unique()->filter()->toArray();
        $customerBalances = $this->getCustomerBalancesInBulk($customerIds);

        $challans->getCollection()->transform(function ($challan) use ($customerBalances) {
            $challan->customer_account_balance = $customerBalances[$challan->customer_id] ?? 0;
            return $challan;
        });

        return $challans;
    }

    /**
     * Get customer account balances in bulk to avoid N+1 queries
     */
    // private function getCustomerBalancesInBulk(array $customerIds): array
    // {
    //     if (empty($customerIds)) {
    //         return [];
    //     }

    //     // Fetch all account balances in a single query using join with transactions
    //     $balances = Account::query()
    //         ->whereIn('accounts.accountable_id', $customerIds)
    //         ->where('accounts.accountable_type', 'Modules\CRM\Models\Customer\Customer')
    //         ->leftJoin('transactions', 'transactions.account_id', '=', 'accounts.id')
    //         ->select('accounts.accountable_id', DB::raw('COALESCE(SUM(transactions.amount), 0) as balance'))
    //         ->groupBy('accounts.accountable_id')
    //         ->get()
    //         ->pluck('balance', 'accountable_id');

    //     return $balances->toArray();
    // }

    private function getCustomerBalancesInBulk(array $customerIds): array
    {
        if (empty($customerIds)) {
            return [];
        }

        // OPTIMIZED: Use subquery for better performance
        $balances = DB::table('accounts')
            ->whereIn('accounts.accountable_id', $customerIds)
            ->where('accounts.accountable_type', 'Modules\CRM\Models\Customer\Customer')
            ->select(
                'accounts.accountable_id',
                DB::raw('(SELECT COALESCE(SUM(transactions.amount), 0)
                     FROM transactions
                     WHERE transactions.account_id = accounts.id
                     AND transactions.deleted_at IS NULL) as balance')
            )
            ->get()
            ->pluck('balance', 'accountable_id');

        return $balances->toArray();
    }

    /**
     * Export report data
     */
    private function exportReport(Request $request, $reportData, $salesReturns, $backupChallans)
    {
        $data = [
            'reportData'   => $reportData,
            'salesOrders'  => $this->getDropdownSalesOrders(),
            'salesReturns' => $salesReturns,
            'customers'    => $this->getCustomersForDropdown(),
            'branches'     => $this->getBranchesForDropdown(),
            'products'     => $this->getProductsForDropdown(),
            'users'        => $this->getUsersForDropdown(),
            'company_info' => $this->getCompanyInfo(),
        ];

        $filename = 'Sales_Report_' . now()->format('Y_m_d_His');

        return (new ExportService())->exportData(
            $data,
            'Sales::reports.export.',
            $filename,
            $request->export_type
        );
    }

    /**
     * Prepare view data
     */
    private function getViewData($paginatedData, $salesReturns, $backupChallans)
    {
        return [
            'reportData'     => $paginatedData,
            'salesOrders'    => $this->getDropdownSalesOrders(),
            'salesReturns'   => $salesReturns,
            'backupChallans' => $backupChallans,
            'customers'      => $this->getCustomersForDropdown(),
            'branches'       => $this->getBranchesForDropdown(),
            'products'       => $this->getProductsForDropdown(),
            'users'          => $this->getUsersForDropdown(),
            'company_info'   => $this->getCompanyInfo(),
        ];
    }

    /**
     * Get sales orders for dropdown (limited)
     */
    private function getDropdownSalesOrders()
    {
        return Cache::remember('sales_report_dropdown_orders', self::DROPDOWN_TTL, function () {
            return SalesOrder::orderBy('created_at', 'desc')->limit(100)->get();
        });
    }

    /**
     * Get customers for dropdown with caching
     */
    private function getCustomersForDropdown()
    {
        return Cache::remember('sales_report_customers', self::CUSTOMER_TTL, function () {
            return Customer::activeCustomers()
                ->select('id', 'company_name', 'address')
                ->orderBy('company_name')
                ->get();
        });
    }

    /**
     * Get branches for dropdown with caching
     */
    private function getBranchesForDropdown()
    {
        return Cache::remember('sales_report_branches', self::BRANCH_TTL, function () {
            return Branch::select('id', 'name')->get();
        });
    }

    /**
     * Get products for dropdown with caching
     */
    private function getProductsForDropdown()
    {
        return Cache::remember('sales_report_products', self::PRODUCT_TTL, function () {
            return ProductCatalog::where('status', 'active')
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Get users for dropdown with caching
     */
    private function getUsersForDropdown()
    {
        return Cache::remember('sales_report_users', self::USER_TTL, function () {
            return User::whereDoesntHave('roles', function ($query) {
                $query->where('slug', 'customer');
            })->select('id', 'name')->get();
        });
    }

    /**
     * Get company info with caching
     */
    private function getCompanyInfo()
    {
        return Cache::remember('sales_report_company_info', self::DROPDOWN_TTL, function () {
            return CompanyInfo::first();
        });
    }

    /**
     * Calculate customer balance (kept for backward compatibility)
     */
    private function calculateCustomerBalance($customer)
    {
        if (! $customer) {
            return 0;
        }

        try {
            if ($customer->account && isset($customer->account->balance)) {
                return $customer->account->balance;
            }

            $totalSales = SalesOrder::where('customer_id', $customer->id)
                ->whereIn('status', ['delivered', 'partial'])
                ->sum('net_amount');

            $totalPaid = $customer->payments()->sum('amount');

            return $totalSales - $totalPaid;

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
     * Determine which record types to include
     */
    private function getIncludeTypes($request)
    {
        if ($request->filled('sales_type')) {
            $salesType = $request->input('sales_type');

            $typeMap = [
                'general_sales' => ['sales_orders' => true, 'sales_return' => false, 'backup_challan' => false],
                'partial_sales' => ['sales_orders' => true, 'sales_return' => false, 'backup_challan' => false],
                'free_sales'    => ['sales_orders' => true, 'sales_return' => false, 'backup_challan' => false],
            ];

            return $typeMap[$salesType] ?? ['sales_orders' => true, 'sales_return' => false, 'backup_challan' => false];
        }

        $invoiceType = $request->input('invoice_type');

        if (! $invoiceType) {
            return ['sales_orders' => true, 'sales_return' => true, 'backup_challan' => true];
        }

        $typeMap = [
            'delivered'      => ['sales_orders' => true, 'sales_return' => false, 'backup_challan' => false],
            'undelivered'    => ['sales_orders' => true, 'sales_return' => false, 'backup_challan' => false],
            'pending'        => ['sales_orders' => true, 'sales_return' => false, 'backup_challan' => false],
            'partial_sales'  => ['sales_orders' => true, 'sales_return' => false, 'backup_challan' => false],
            'free_sales'     => ['sales_orders' => true, 'sales_return' => false, 'backup_challan' => false],
            'sales_return'   => ['sales_orders' => false, 'sales_return' => true, 'backup_challan' => false],
            'backup_challan' => ['sales_orders' => false, 'sales_return' => false, 'backup_challan' => true],
        ];

        return $typeMap[$invoiceType] ?? ['sales_orders' => true, 'sales_return' => true, 'backup_challan' => true];
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

        if ($request->filled('invoice_type')) {
            switch ($request->invoice_type) {
                case 'delivered':
                    $query->where('status', 'delivered');
                    break;
                case 'undelivered':
                    $query->where('status', 'approved')->where('sales_type', 'general_sales');
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
