<?php

namespace Modules\CRM\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\GeoLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Account\Models\Transaction;
use Modules\CRM\Models\Customer\Customer;
use Modules\Licenses\Models\USGOrOPGLicenseRequisition;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesReturn;
use Modules\Inventory\Services\ExportService;

class CustomerBalanceReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $dueType = $request->input('due_type', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $recoveryPercentage = $request->input('recovery_percentage');
        $search = $request->input('search');

        // Build the report data
        $reportData = $this->buildReportData($dueType, $startDate, $endDate, $recoveryPercentage, $search);

        // Calculate totals
        $totals = $this->calculateTotals($reportData);

        // Handle export
        if ($request->filled('export_type')) {
            $filterData = $this->getFilterData();
            return $this->exportReport($reportData, $filterData, $totals, $request->export_type, [
                'due_type' => $dueType,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'recovery_percentage' => $recoveryPercentage,
                'search' => $search
            ]);
        }

        // Only load filter data when needed (not for export)
        $filterData = $this->getFilterData();

        return view('CRM::customer-balance-details.index', [
            'reportData' => $reportData,
            'customersearch' => $filterData['customersearch'],
            'company_info' => $filterData['company_info'],
            'filters' => [
                'due_type' => $dueType,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'recovery_percentage' => $recoveryPercentage,
                'search' => $search
            ],
            'divisions' => $filterData['divisions'],
            'districts' => $filterData['districts'],
            'totals' => $totals
        ]);
    }

    private function buildReportData($dueType, $startDate, $endDate, $recoveryPercentage, $search)
    {
        // Parse dates
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now();
        $beforeStartDate = $start->copy()->subDay()->endOfDay();

        // Get customer IDs first (lightweight query)
        $customerIds = $this->getFilteredCustomerIds($dueType, $search);

        if (empty($customerIds)) {
            return collect([]);
        }

        // Pre-fetch all data with optimized queries
        $aggregatedData = $this->fetchAggregatedData($customerIds, $start, $end, $beforeStartDate);

        // Get customer basic info
        $customers = Customer::whereIn('id', $customerIds)
            ->select('id', 'company_name', 'phone','address')
            ->get()
            ->keyBy('id');

        // Build report data
        $reportData = [];
        foreach ($customerIds as $customerId) {
            $customer = $customers[$customerId] ?? null;
            if (!$customer) {
                continue;
            }
            
            $customerData = [
                'customer_id' => $customerId,
                'account_id' => $customer->getAccount()->id ?? null,
                'customer_name' => $customer->company_name,
                'address' => $customer->address,
                'phone' => $customer->phone,
                'has_machine_code' => $aggregatedData['machine_codes'][$customerId] ?? false,
                'opening_balance' => $aggregatedData['opening_balances'][$customerId] ?? 0,
                'sales' => $aggregatedData['period_sales'][$customerId] ?? 0,
                'sales_return' => $aggregatedData['period_returns'][$customerId] ?? 0,
                'collection' => $aggregatedData['period_collections'][$customerId] ?? 0,
            ];

            // Calculate derived values
            $customerData['due'] = $customerData['sales'] - $customerData['sales_return'] - $customerData['collection'];
            $customerData['closing_balance'] = $customerData['opening_balance'] + $customerData['sales'] 
                - $customerData['sales_return'] - $customerData['collection'];

            // Calculate recovery percentage
            $recoveryPerc = 0;
            if ($customerData['opening_balance'] > 0) {
                $recoveryPerc = (($customerData['opening_balance'] - $customerData['closing_balance']) * 100) / $customerData['opening_balance'];
            }
            $customerData['recovery_percentage'] = $recoveryPerc;

            // Skip customers where all values are 0
            //TODO: This was commented out as per request, but can be re-enabled if needed to filter out zero-activity customers
            /*if ($customerData['opening_balance'] == 0 
                && $customerData['sales'] == 0 
                && $customerData['sales_return'] == 0 
                && $customerData['collection'] == 0 
                && $customerData['due'] == 0 
                && $customerData['closing_balance'] == 0) {
                continue;
            }
            */
            
            // Filter by recovery percentage if specified
            if ($recoveryPercentage && !$this->matchesRecoveryPercentage($recoveryPerc, $recoveryPercentage)) {
                continue;
            }

            $reportData[] = $customerData;
        }

        return collect($reportData);
    }

    private function getFilteredCustomerIds($dueType, $search)
    {
        $query = Customer::actived()->select('id');

        // Apply due type filter
        if ($dueType === 'machine_code') {
            $query->whereHas('usgOrOpgLicenseRequisitions');
        } elseif ($dueType === 'old_due') {
            $query->whereDoesntHave('usgOrOpgLicenseRequisitions');
        }

        if(request()->filled('division_id')){
            $query->whereHas('area', function($q){
                $q->where('division_id', request()->division_id);
            });
        }

        if(request()->filled('district_id')){
            $query->whereHas('area', function($q){
                $q->where('district_id', request()->district_id);
            });
        }


        // Apply search filter
        if ($search) {
            $query->where('id', $search);
        }

        return $query->pluck('id')->toArray();
    }

    private function fetchAggregatedData($customerIds, $start, $end, $beforeStartDate)
    {
        // Fetch machine codes existence in single query
        $machineCodes = DB::table('u_s_g_or_o_p_g_license_requisitions')
            ->whereIn('customer_id', $customerIds)
            ->whereNull('deleted_at')
            ->groupBy('customer_id')
            ->pluck('customer_id')
            ->flip()
            ->map(fn() => true)
            ->toArray();

        // Fetch opening balance data (before start date)
        $openingSales = DB::table('sales_orders')
            ->whereIn('customer_id', $customerIds)
            ->whereIn('status', ['delivered', 'partial', 'approved'])
            ->where('invoice_date', '<=', $beforeStartDate)
            ->whereNull('deleted_at')
            ->groupBy('customer_id')
            ->selectRaw('customer_id, SUM(net_amount) as total')
            ->pluck('total', 'customer_id')
            ->toArray();

        $openingReturns = DB::table('sales_returns')
            ->whereIn('customer_id', $customerIds)
            ->where('return_date', '<=', $beforeStartDate)
            ->whereNull('deleted_at')
            ->groupBy('customer_id')
            ->selectRaw('customer_id, SUM(net_amount) as total')
            ->pluck('total', 'customer_id')
            ->toArray();

        // Fetch period data (within date range)
        $periodSales = DB::table('sales_orders')
            ->whereIn('customer_id', $customerIds)
            ->whereIn('status', ['delivered', 'partial', 'approved'])
            ->whereBetween('invoice_date', [$start, $end])
            ->whereNull('deleted_at')
            ->groupBy('customer_id')
            ->selectRaw('customer_id, SUM(net_amount) as total')
            ->pluck('total', 'customer_id')
            ->toArray();

        $periodReturns = DB::table('sales_returns')
            ->whereIn('customer_id', $customerIds)
            ->whereBetween('return_date', [$start, $end])
            ->whereNull('deleted_at')
            ->groupBy('customer_id')
            ->selectRaw('customer_id, SUM(net_amount) as total')
            ->pluck('total', 'customer_id')
            ->toArray();

        // Fetch collections (more complex due to account relationship)
        $openingCollections = $this->fetchBulkCollections($customerIds, null, $beforeStartDate);
        $periodCollections = $this->fetchBulkCollections($customerIds, $start, $end);

        // Calculate opening balances
        $openingBalances = [];
        foreach ($customerIds as $customerId) {
            $sales = $openingSales[$customerId] ?? 0;
            $returns = $openingReturns[$customerId] ?? 0;
            $collections = $openingCollections[$customerId] ?? 0;
            
            $openingBalances[$customerId] = $sales - $returns - $collections;
        }

        return [
            'machine_codes' => $machineCodes,
            'opening_balances' => $openingBalances,
            'period_sales' => $periodSales,
            'period_returns' => $periodReturns,
            'period_collections' => $periodCollections
        ];
    }

    private function fetchBulkCollections($customerIds, $startDate = null, $endDate = null)
    {
        // Get customers and build account mapping using getAccount() method
        $customers = Customer::whereIn('id', $customerIds)
            ->with('accounts')
            ->get();

        $accountIds = [];
        $customerAccountMap = [];

        foreach ($customers as $customer) {
            $account = $customer->getAccount();
            if ($account) {
                $accountIds[] = $account->id;
                $customerAccountMap[$account->id] = $customer->id;
            }
        }

        if (empty($accountIds)) {
            return [];
        }

        // Fetch all transactions in one query
        $query = DB::table('transactions')
            ->whereIn('account_id', $accountIds)
            ->where('balance_type', 'credit')
            ->whereNull('deleted_at')
            ->groupBy('account_id')
            ->selectRaw('account_id, SUM(credit_amount) as total');

        if ($startDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
            $query->whereDate('created_at', '<=', $endDate);
        }


        $collections = $query->pluck('total', 'account_id');

        // Map back to customer IDs
        $result = [];
        foreach ($collections as $accountId => $amount) {
            if (isset($customerAccountMap[$accountId])) {
                $result[$customerAccountMap[$accountId]] = $amount;
            }
        }

        return $result;
    }

    private function matchesRecoveryPercentage($percentage, $filter)
    {
        return match($filter) {
            'below_10' => $percentage < 10,
            '10_20' => $percentage >= 10 && $percentage <= 20,
            '21_30' => $percentage >= 21 && $percentage <= 30,
            '31_40' => $percentage >= 31 && $percentage <= 40,
            '41_50' => $percentage >= 41 && $percentage <= 50,
            '51_60' => $percentage >= 51 && $percentage <= 60,
            '61_70' => $percentage >= 61 && $percentage <= 70,
            '71_80' => $percentage >= 71 && $percentage <= 80,
            'above_80' => $percentage > 80,
            default => true,
        };
    }

    private function calculateTotals($reportData)
    {
        return [
            'total_opening_balance' => $reportData->sum('opening_balance'),
            'total_sales' => $reportData->sum('sales'),
            'total_sales_return' => $reportData->sum('sales_return'),
            'total_collection' => $reportData->sum('collection'),
            'total_due' => $reportData->sum('due'),
            'total_closing_balance' => $reportData->sum('closing_balance')
        ];
    }

    /**
     * Get filter dropdown data
     */
    private function getFilterData()
    {
        // Cache company info as it rarely changes
        $companyInfo = Cache::remember('company_info', 3600, function () {
            return CompanyInfo::first();
        });

        return [
            'customersearch' => Customer::actived()
                ->select('id', 'company_name')
                ->orderBy('company_name')
                ->get(),
            'company_info' => $companyInfo,
            'divisions' => GeoLocation::where('type', 'Division')->get(),
            'districts' => GeoLocation::where('type', 'District')->get(),
        ];
    }

    /**
     * Export report using ExportService
     */
    private function exportReport($reportData, $filterData, $totals, $exportType, $filters)
    {
        $data = array_merge([
            'reportData' => $reportData,
            'totals' => $totals,
            'filters' => $filters
        ], $filterData);
        
        $filename = 'Customer_Balance_Details_' . now()->format('Y_m_d_His');
        
        return (new ExportService())->exportData(
            $data,
            'CRM::customer-balance-details.export.',
            $filename,
            $exportType
        );
    }

    public function getCustomerLedger($customerId)
    {
        $customer = Customer::select('id', 'company_name', 'phone')
            ->with([
                'salesOrders' => fn($q) => $q->select('id', 'customer_id', 'invoice_date', 'net_amount')
                    ->latest('invoice_date')
                    ->limit(20),
                'salesReturns' => fn($q) => $q->select('id', 'customer_id', 'return_date', 'net_amount')
                    ->latest('return_date')
                    ->limit(20)
            ])
            ->findOrFail($customerId);
        
        // Get transactions for ledger
        $account = $customer->getAccount();
        
        if (!$account) {
            return view('CRM::reports.partials.customer-ledger', [
                'customer' => $customer,
                'transactions' => collect([])
            ]);
        }

        $transactions = Transaction::where('account_id', $account->id)
            ->select('id', 'account_id', 'date', 'balance_type', 'credit_amount', 'debit_amount', 'description')
            ->latest('date')
            ->latest('id')
            ->limit(50)
            ->get();

        return view('CRM::reports.partials.customer-ledger', compact('customer', 'transactions'));
    }
}