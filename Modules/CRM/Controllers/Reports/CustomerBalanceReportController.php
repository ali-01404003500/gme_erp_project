<?php
namespace Modules\CRM\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\GeoLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Transaction;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Services\ExportService;

class CustomerBalanceReportController extends Controller
{
                                        // -------------------------------------------------------------------------
                                        // CACHE TTL CONSTANTS
                                        // -------------------------------------------------------------------------
                                        // Optimized for 1-second load time:
                                        // - REPORT_TTL: 60s for near-real-time data (aggregated report rows)
                                        // - FILTER_TTL: 1hr for customer list dropdown (rarely changes)
                                        // - GEO_TTL: 24hrs for divisions/districts (static data)
                                        // - COMPANY_TTL: 1hr for company info (static data)
    private const REPORT_TTL  = 60;     // 1 min  – aggregated report rows
    private const FILTER_TTL  = 3_600;  // 1 hr   – customer list dropdown
    private const GEO_TTL     = 86_400; // 24 hrs – divisions / districts
    private const COMPANY_TTL = 3_600;  // 1 hr   – company info

    // -------------------------------------------------------------------------
    // CHUNK SIZE
    // -------------------------------------------------------------------------
    // Optimized chunk size for large datasets. Prevents IN() clause overflow
    // and reduces query execution time by batching large customer lists.
    private const CHUNK_SIZE = 1000;

    // -------------------------------------------------------------------------
    // QUERY OPTIMIZATION FLAGS
    // -------------------------------------------------------------------------
    // Use query cache for repeated queries within the same request
    private const USE_QUERY_CACHE = true;

    // =========================================================================
    // PUBLIC ENTRY POINT
    // =========================================================================
    // public function index(Request $request)
    // {
    //     $filters = $this->extractFilters($request);

    //     $reportData = $this->buildReportData($filters);
    //     $totals     = $this->calculateTotals($reportData);

    //     if ($request->filled('export_type')) {
    //         $filterData = $this->getFilterData();
    //         return $this->exportReport($reportData, $filterData, $totals, $request->export_type, $filters);
    //     }

    //     $filterData = $this->getFilterData();

    //     return view('CRM::customer-balance-details.index', [
    //         'reportData'     => $reportData,
    //         'customersearch' => $filterData['customersearch'],
    //         'company_info'   => $filterData['company_info'],
    //         'filters'        => $filters,
    //         'divisions'      => $filterData['divisions'],
    //         'districts'      => $filterData['districts'],
    //         'totals'         => $totals,
    //     ]);
    // }

    public function index(Request $request)
    {
        $filters = $this->extractFilters($request);

        // রিপোর্ট ডাটা বিল্ড করা
        $allReportData = $this->computeReportData($filters);

        // পেজিনেশন - প্রতি পেজে ৫০টি রেকর্ড
        $perPage     = $request->input('per_page', 50);
        $currentPage = $request->input('page', 1);

        // পেজিনেটেড ডাটা তৈরি করা
        $reportData = new \Illuminate\Pagination\LengthAwarePaginator(
            $allReportData->forPage($currentPage, $perPage),
            $allReportData->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // টোটাল ক্যালকুলেশন
        $totals = $this->calculateTotals($allReportData);

        // এক্সপোর্ট চেক
        if ($request->filled('export_type')) {
            $filterData = $this->getFilterData();
            return $this->exportReport($allReportData, $filterData, $totals, $request->export_type, $filters);
        }

        $filterData = $this->getFilterData();

        return view('CRM::customer-balance-details.index', [
            'reportData'     => $reportData,
            'customersearch' => $filterData['customersearch'],
            'company_info'   => $filterData['company_info'],
            'filters'        => $filters,
            'divisions'      => $filterData['divisions'],
            'districts'      => $filterData['districts'],
            'totals'         => $totals,
        ]);
    }

    // =========================================================================
    // FILTER HELPERS
    // =========================================================================

    /** Pull all filter params into one array so every method shares the same shape. */
    private function extractFilters(Request $request): array
    {
        return [
            'due_type'            => $request->input('due_type', 'all'),
            'start_date'          => $request->input('start_date'),
            'end_date'            => $request->input('end_date'),
            'recovery_percentage' => $request->input('recovery_percentage'),
            'search'              => $request->input('search'),
            'division_id'         => $request->input('division_id'),
            'district_id'         => $request->input('district_id'),
        ];
    }

    // =========================================================================
    // REPORT BUILDER
    // =========================================================================

    private function buildReportData(array $filters)
    {
        // ------------------------------------------------------------------
        // OPTIMISATION 1 – Stable cache key
        // Cache the whole report keyed by the exact filter combo. The key is
        // an MD5 hash so it is always a fixed, safe length regardless of input.
        // ------------------------------------------------------------------
        $cacheKey = 'cbr_report_' . md5(serialize($filters));

        return Cache::remember($cacheKey, self::REPORT_TTL, function () use ($filters) {
            return $this->computeReportData($filters);
        });
    }

    // private function computeReportData(array $filters): \Illuminate\Support\Collection
    // {
    //     $start           = $filters['start_date'] ? Carbon::parse($filters['start_date']) : Carbon::now()->startOfMonth();
    //     $end             = $filters['end_date']   ? Carbon::parse($filters['end_date'])   : Carbon::now();
    //     $beforeStartDate = $start->copy()->subDay()->endOfDay();

    //     // ------------------------------------------------------------------
    //     // OPTIMISATION 2 – Fetch only the columns we need, no eager-loads.
    //     // The original code loaded full Eloquent models then called
    //     // $customer->getAccount() inside a loop – an N+1 query.
    //     // We avoid both by selecting only required columns and resolving
    //     // account IDs in a single bulk query later.
    //     // ------------------------------------------------------------------
    //     $customers    = $this->fetchCustomers($filters);
    //     $customerIds  = $customers->keys()->toArray();

    //     if (empty($customerIds)) {
    //         return collect([]);
    //     }

    //     // ------------------------------------------------------------------
    //     // OPTIMISATION 3 – All heavy aggregation happens in one pass per
    //     // data type, chunked so we never exceed DB parameter limits.
    //     // ------------------------------------------------------------------
    //     $aggregated = $this->fetchAggregatedData($customerIds, $start, $end, $beforeStartDate);

    //     // ------------------------------------------------------------------
    //     // OPTIMISATION 4 – Build result rows in a single PHP loop; no
    //     // further DB calls happen here.
    //     // ------------------------------------------------------------------
    //     $reportData = [];
    //     foreach ($customerIds as $customerId) {
    //         $customer = $customers[$customerId] ?? null;
    //         if (!$customer) {
    //             continue;
    //         }

    //         $row = [
    //             'customer_id'      => $customerId,
    //             'account_id'       => $aggregated['account_ids'][$customerId]  ?? null,
    //             'customer_name'    => $customer->company_name,
    //             'address'          => $customer->address,
    //             'phone'            => $customer->phone,
    //             'has_machine_code' => $aggregated['machine_codes'][$customerId] ?? false,
    //             'opening_balance'  => $aggregated['opening_balances'][$customerId] ?? 0,
    //             'sales'            => $aggregated['period_sales'][$customerId]     ?? 0,
    //             'sales_return'     => $aggregated['period_returns'][$customerId]   ?? 0,
    //             'collection'       => $aggregated['period_collections'][$customerId] ?? 0,
    //             'charge'           => $aggregated['period_charges'][$customerId] ?? 0,
    //             'waiver'           => $aggregated['period_waivers'][$customerId] ?? 0,
    //         ];

    //         // Get transactions
    //         $transaction = Transaction::query()
    //             ->searchByField('company_id')
    //             ->where('account_id', $aggregated['account_ids'][$customerId])
    //             ->when($start, function ($q) use ($start) {
    //                 $q->whereDate('transaction_date', '>=', $start);
    //             })
    //             ->when($end, function ($q) use ($end) {
    //                 $q->whereDate('transaction_date', '<=', $end);
    //             })
    //             ->selectRaw('
    //                 SUM(debit_amount) as total_debit,
    //                 SUM(credit_amount) as total_credit
    //             ')
    //             ->first();

    //         $row['closing_balance'] = $transaction->total_debit - $transaction->total_credit;

    //         $row['due']             = $row['sales'] - $row['sales_return'] - $row['collection'];
    //         //$row['closing_balance'] = $row['opening_balance'] + $row['sales'] - $row['collection'] + $row['charge'];

    //         $recoveryPerc = 0;
    //         if ($row['opening_balance'] > 0) {
    //             $recoveryPerc = (($row['opening_balance'] - $row['closing_balance']) * 100)
    //                             / $row['opening_balance'];
    //         }
    //         $row['recovery_percentage'] = $recoveryPerc;

    //         if (isset($filters['recovery_percentage'])
    //             && $filters['recovery_percentage']
    //             && !$this->matchesRecoveryPercentage($recoveryPerc, $filters['recovery_percentage'])
    //         ) {
    //             continue;
    //         }

    //         $reportData[] = $row;
    //     }

    //     return collect($reportData);
    // }

    // =========================================================================
    // CUSTOMER FETCH
    // =========================================================================

    /**
     * OPTIMIZED: Bulk fetch transaction balances for all accounts
     * Single query instead of N+1 queries
     */
    private function fetchBulkTransactionBalances(array $accountIds, Carbon $start, Carbon $end): array
    {
        if (empty($accountIds)) {
            return [];
        }

        $balances = DB::table('transactions')
            ->whereIn('account_id', $accountIds)
            ->whereNull('deleted_at')
            ->whereDate('transaction_date', '>=', $start)
            ->whereDate('transaction_date', '<=', $end)
            ->select(
                'account_id',
                DB::raw('SUM(debit_amount) as total_debit'),
                DB::raw('SUM(credit_amount) as total_credit')
            )
            ->groupBy('account_id')
            ->get()
            ->mapWithKeys(fn($item) => [
                $item->account_id => [
                    'total_debit'  => (float) $item->total_debit,
                    'total_credit' => (float) $item->total_credit,
                ],
            ])
            ->toArray();

        return $balances;
    }
    private function computeReportData(array $filters): \Illuminate\Support\Collection
    {
        $start           = $filters['start_date'] ? Carbon::parse($filters['start_date']) : Carbon::now()->startOfMonth();
        $end             = $filters['end_date'] ? Carbon::parse($filters['end_date']) : Carbon::now();
        $beforeStartDate = $start->copy()->subDay()->endOfDay();

        $customers   = $this->fetchCustomers($filters);
        $customerIds = $customers->keys()->toArray();

        if (empty($customerIds)) {
            return collect([]);
        }

        $aggregated = $this->fetchAggregatedData($customerIds, $start, $end, $beforeStartDate);

        // OPTIMIZED: Bulk fetch transaction balances for all customers at once
        $accountIds          = array_filter($aggregated['account_ids']);
        $transactionBalances = $this->fetchBulkTransactionBalances($accountIds, $start, $end);

        $reportData = [];
        foreach ($customerIds as $customerId) {
            $customer = $customers[$customerId] ?? null;
            if (! $customer) {
                continue;
            }

            $accountId = $aggregated['account_ids'][$customerId] ?? null;

            $row = [
                'customer_id'      => $customerId,
                'account_id'       => $accountId,
                'customer_name'    => $customer->company_name,
                'address'          => $customer->address,
                'phone'            => $customer->phone,
                'has_machine_code' => $aggregated['machine_codes'][$customerId] ?? false,
                'opening_balance'  => $aggregated['opening_balances'][$customerId] ?? 0,
                'sales'            => $aggregated['period_sales'][$customerId] ?? 0,
                'sales_return'     => $aggregated['period_returns'][$customerId] ?? 0,
                'collection'       => $aggregated['period_collections'][$customerId] ?? 0,
                'charge'           => $aggregated['period_charges'][$customerId] ?? 0,
                'waiver'           => $aggregated['period_waivers'][$customerId] ?? 0,
            ];

            // OPTIMIZED: Get from pre-fetched balances instead of separate query
            $balance                = $transactionBalances[$accountId] ?? ['total_debit' => 0, 'total_credit' => 0];
            $row['closing_balance'] = $balance['total_debit'] - $balance['total_credit'];
            $row['due']             = $row['sales'] - $row['sales_return'] - $row['collection'];

            $recoveryPerc = 0;
            if ($row['opening_balance'] > 0) {
                $recoveryPerc = (($row['opening_balance'] - $row['closing_balance']) * 100) / $row['opening_balance'];
            }
            $row['recovery_percentage'] = $recoveryPerc;

            if (isset($filters['recovery_percentage']) && $filters['recovery_percentage'] && ! $this->matchesRecoveryPercentage($recoveryPerc, $filters['recovery_percentage'])) {
                continue;
            }

            $reportData[] = $row;
        }

        return collect($reportData);
    }
    /**
     * Returns a Collection keyed by customer ID.
     * Only the columns we actually render are selected.
     */
    private function fetchCustomers(array $filters): \Illuminate\Support\Collection
    {
        $query = Customer::actived()
            ->select('id', 'company_name', 'phone', 'address', 'company_place_id', 'customer_id')->with('area');
        // company_place_id is the FK; eager-loading area prevents lazy hits
        // inside whereHas callbacks and any downstream blade rendering.

        if ($filters['due_type'] === 'machine_code') {
            $query->whereHas('usgOrOpgLicenseRequisitions');
        } elseif ($filters['due_type'] === 'old_due') {
            $query->whereDoesntHave('usgOrOpgLicenseRequisitions');
        }

        if (! empty($filters['division_id'])) {
            $query->whereHas('area', fn($q) => $q->where('division_id', $filters['division_id']));
        }

        if (! empty($filters['district_id'])) {
            $query->whereHas('area', fn($q) => $q->where('district_id', $filters['district_id']));
        }

        if (! empty($filters['search'])) {
            $query->where('id', $filters['search']);
        }

        return $query->get()->keyBy('id');
    }

    // =========================================================================
    // AGGREGATED DATA FETCH
    // =========================================================================

    private function fetchAggregatedData(
        array $customerIds,
        Carbon $start,
        Carbon $end,
        Carbon $beforeStartDate
    ): array {
        // ------------------------------------------------------------------
        // OPTIMISATION 5 – Chunked queries.
        // Large IN() lists are slow and can hit the DB's max_allowed_packet
        // or parameter limit. We chunk and merge the results in PHP.
        // ------------------------------------------------------------------
        $chunks = array_chunk($customerIds, self::CHUNK_SIZE);

        $machineCodes        = [];
        $salesData           = [];
        $returnsData         = [];
        $openingBalances2021 = [];
        $accountMap          = []; // customer_id => account_id

        foreach ($chunks as $chunk) {
            // ---- machine codes ------------------------------------------------
            $mc  = DB::table('u_s_g_or_o_p_g_license_requisitions')
                ->whereIn('customer_id', $chunk)
                ->whereNull('deleted_at')
                ->groupBy('customer_id')
                ->pluck('customer_id')
                ->flip()
                ->map(fn() => true)
                ->toArray();
            $machineCodes += $mc;

            // ---- sales (opening + period in one conditional-aggregate query) --
            // OPTIMISATION 6 – Two SUM(CASE WHEN …) in a single SQL round-trip
            // instead of two separate queries per period.
            $sd  = DB::table('sales_orders')
                ->whereIn('customer_id', $chunk)
                ->whereIn('status', ['delivered', 'partial', 'approved'])
                ->whereNull('deleted_at')
                ->groupBy('customer_id')
                ->selectRaw(
                    'customer_id,
                     SUM(CASE WHEN invoice_date <= ? THEN net_amount ELSE 0 END) AS opening_sales,
                     SUM(CASE WHEN invoice_date BETWEEN ? AND ? THEN net_amount ELSE 0 END) AS period_sales',
                    [$beforeStartDate, $start, $end]
                )
                ->get()
                ->mapWithKeys(fn($r) => [
                    $r->customer_id => [
                        'opening' => (float) $r->opening_sales,
                        'period'  => (float) $r->period_sales,
                    ],
                ])
                ->toArray();
            $salesData += $sd;

            // ---- returns (same pattern) ----------------------------------------
            $rd  = DB::table('sales_returns')
                ->whereIn('customer_id', $chunk)
                ->whereNull('deleted_at')
                ->groupBy('customer_id')
                ->selectRaw(
                    'customer_id,
                     SUM(CASE WHEN return_date <= ? THEN net_amount ELSE 0 END) AS opening_returns,
                     SUM(CASE WHEN return_date BETWEEN ? AND ? THEN net_amount ELSE 0 END) AS period_returns',
                    [$beforeStartDate, $start, $end]
                )
                ->get()
                ->mapWithKeys(fn($r) => [
                    $r->customer_id => [
                        'opening' => (float) $r->opening_returns,
                        'period'  => (float) $r->period_returns,
                    ],
                ])
                ->toArray();
            $returnsData += $rd;

            // ---- opening balances (static per customer) -------------------------
            $ob  = DB::table('customer_settings')
                ->whereIn('customer_id', $chunk)
                ->pluck('opening_balance', 'customer_id')
                ->toArray();
            $openingBalances2021 += $ob;

            // ---- account IDs (needed for ledger links) --------------------------
            // OPTIMISATION 7 – Fetch account IDs in bulk here rather than calling
            // $customer->getAccount() inside the render loop (N+1 eliminated).
            $am  = DB::table('accounts')
                ->where('accountable_type', 'Modules\CRM\Models\Customer\Customer')
                ->whereIn('accountable_id', $chunk)
                ->where('account_subsidiary_id', 1005)
                ->pluck('id', 'accountable_id')
                ->toArray();
            $accountMap += $am;
        }

        // ------------------------------------------------------------------
        // OPTIMISATION 8 – Collections fetched in two bulk queries (one for
        // opening, one for period) rather than once per customer.
        // ------------------------------------------------------------------
        $accountIds           = array_values($accountMap);
        $accountIdToCustomer  = array_flip($accountMap); // account_id => customer_id

        $openingCollections  = $this->fetchBulkCollections($accountIds, $accountIdToCustomer, null, $beforeStartDate);
        $periodCollections   = $this->fetchBulkCollections($accountIds, $accountIdToCustomer, $start, $end);
        $periodCharges       = $this->fetchBulkCharges($accountIds, $accountIdToCustomer, $start, $end);
        $periodWaivers       = $this->fetchBulkWaivers($accountIds, $accountIdToCustomer, $start, $end);
        // ---- Assemble final arrays -----------------------------------------
        $openingBalances  = [];
        $periodSales      = [];
        $periodReturns    = [];

        foreach ($customerIds as $customerId) {
            $sd = $salesData[$customerId] ?? ['opening' => 0, 'period' => 0];
            $rd = $returnsData[$customerId] ?? ['opening' => 0, 'period' => 0];

            $openingBalances[$customerId] =
                ($openingBalances2021[$customerId] ?? 0)
                 + $sd['opening']
                 - $rd['opening']
                 - ($openingCollections[$customerId] ?? 0);

            $periodSales[$customerId]   = $sd['period'];
            $periodReturns[$customerId] = $rd['period'];
        }

        return [
            'machine_codes'      => $machineCodes,
            'account_ids'        => $accountMap, // ← now returned directly
            'opening_balances'   => $openingBalances,
            'period_sales'       => $periodSales,
            'period_returns'     => $periodReturns,
            'period_collections' => $periodCollections,
            'period_charges'     => $periodCharges,
            'period_waivers'     => $periodWaivers,
        ];
    }

    // =========================================================================
    // COLLECTIONS HELPER
    // =========================================================================

    /**
     * OPTIMISATION 9 – Accepts pre-resolved account IDs to avoid re-querying
     * the accounts table on every call. One SQL per date range instead of one
     * per customer.
     *
     * @param  int[]   $accountIds          All account IDs to aggregate.
     * @param  array   $accountIdToCustomer Map of account_id => customer_id.
     * @param  Carbon|null $startDate
     * @param  Carbon|null $endDate
     * @return array   customer_id => total credit amount
     */
    private function fetchBulkCollections(
        array $accountIds,
        array $accountIdToCustomer,
        ?Carbon $startDate,
        ?Carbon $endDate
    ): array {
        if (empty($accountIds)) {
            return [];
        }

        $query = DB::table('transactions')
            ->whereIn('account_id', $accountIds)
            ->where('balance_type', 'credit')
            ->whereNull('deleted_at')
            ->groupBy('account_id')
            ->selectRaw('account_id, SUM(credit_amount) AS total');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate->startOfDay());
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate->endOfDay());
        }

        $result = [];
        foreach ($query->pluck('total', 'account_id') as $accountId => $amount) {
            $customerId = $accountIdToCustomer[$accountId] ?? null;
            if ($customerId !== null) {
                $result[$customerId] = (float) $amount;
            }
        }

        return $result;
    }

    private function fetchBulkCharges(
        array $accountIds,
        array $accountIdToCustomer,
        ?Carbon $startDate,
        ?Carbon $endDate
    ): array {
        if (empty($accountIds)) {
            return [];
        }

        $query = DB::table('transactions')
            ->whereIn('account_id', $accountIds)
            ->where('balance_type', 'debit')
            ->where('description', 'Collection Charge')
            ->whereNull('deleted_at')
            ->groupBy('account_id')
            ->selectRaw('account_id, SUM(debit_amount) AS total');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate->startOfDay());
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate->endOfDay());
        }

        $result = [];
        foreach ($query->pluck('total', 'account_id') as $accountId => $amount) {
            $customerId = $accountIdToCustomer[$accountId] ?? null;
            if ($customerId !== null) {
                $result[$customerId] = (float) $amount;
            }
        }

        return $result;
    }

    private function fetchBulkWaivers(
        array $accountIds,
        array $accountIdToCustomer,
        ?Carbon $startDate,
        ?Carbon $endDate
    ): array {
        if (empty($accountIds)) {
            return [];
        }

        $query = DB::table('transactions')
            ->whereIn('account_id', $accountIds)
            ->where('balance_type', 'credit')
            ->where('description', 'Customer Waiver Payment')
            ->whereNull('deleted_at')
            ->groupBy('account_id')
            ->selectRaw('account_id, SUM(credit_amount) AS total');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate->startOfDay());
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate->endOfDay());
        }

        $result = [];
        foreach ($query->pluck('total', 'account_id') as $accountId => $amount) {
            $customerId = $accountIdToCustomer[$accountId] ?? null;
            if ($customerId !== null) {
                $result[$customerId] = (float) $amount;
            }
        }

        return $result;
    }

    // =========================================================================
    // TOTALS
    // =========================================================================

    private function calculateTotals(\Illuminate\Support\Collection $reportData): array
    {
        return [
            'total_opening_balance' => $reportData->sum('opening_balance'),
            'total_sales'           => $reportData->sum('sales'),
            'total_sales_return'    => $reportData->sum('sales_return'),
            'total_collection'      => $reportData->sum('collection'),
            'total_due'             => $reportData->sum('due'),
            'total_closing_balance' => $reportData->sum('closing_balance'),
            'total_charge'          => $reportData->sum('charge'),
            'total_waiver'          => $reportData->sum('waiver'),
        ];
    }

    // =========================================================================
    // RECOVERY % FILTER
    // =========================================================================

    private function matchesRecoveryPercentage(float $percentage, string $filter): bool
    {
        return match ($filter) {
            'below_10' => $percentage < 10,
            '10_20'    => $percentage >= 10 && $percentage <= 20,
            '21_30'    => $percentage >= 21 && $percentage <= 30,
            '31_40'    => $percentage >= 31 && $percentage <= 40,
            '41_50'    => $percentage >= 41 && $percentage <= 50,
            '51_60'    => $percentage >= 51 && $percentage <= 60,
            '61_70'    => $percentage >= 61 && $percentage <= 70,
            '71_80'    => $percentage >= 71 && $percentage <= 80,
            'above_80' => $percentage > 80,
            default    => true,
        };
    }

    // =========================================================================
    // FILTER DROPDOWNS  (cached aggressively – they change rarely)
    // =========================================================================

    private function getFilterData(): array
    {
        return [
            // OPTIMISATION 10 – Customer list cached for 1 h; large tables
            // make this query expensive when run on every page load.
            // OPTIMISATION 11 – Eager-load 'area' with only the columns needed so
            // the blade loop {{ $value->area?->area }} never fires a lazy query.
            'customersearch' => Cache::remember('cbr_customer_list', self::FILTER_TTL, fn() =>
                Customer::actived()
                    ->select('id', 'company_name', 'company_place_id')
                    ->with(['area']) // ← kills the N+1
                    ->orderBy('company_name')
                    ->get()
            ),
            'company_info'   => Cache::remember('company_info', self::COMPANY_TTL, fn() =>
                CompanyInfo::first()
            ),
            'divisions'      => Cache::remember('geo_divisions', self::GEO_TTL, fn() =>
                GeoLocation::where('type', 'Division')->get()
            ),
            'districts'      => Cache::remember('geo_districts', self::GEO_TTL, fn() =>
                GeoLocation::where('type', 'District')->get()
            ),
        ];
    }

    // =========================================================================
    // EXPORT
    // =========================================================================

    private function exportReport(
        \Illuminate\Support\Collection $reportData,
        array $filterData,
        array $totals,
        string $exportType,
        array $filters
    ) {
        $data = array_merge(
            ['reportData' => $reportData, 'totals' => $totals, 'filters' => $filters],
            $filterData
        );

        $filename = 'Customer_Balance_Details_' . now()->format('Y_m_d_His');

        return (new ExportService())->exportData(
            $data,
            'CRM::customer-balance-details.export.',
            $filename,
            $exportType
        );
    }

    // =========================================================================
    // CUSTOMER LEDGER (unchanged logic, minor cleanup)
    // =========================================================================

    public function getCustomerLedger(int $customerId)
    {
        $customer = Customer::select('id', 'company_name', 'phone', 'address', 'company_place_id')
            ->with([
                'salesOrders'  => fn($q)  => $q->select('id', 'customer_id', 'invoice_date', 'net_amount')
                    ->latest('invoice_date')->limit(20),
                'salesReturns' => fn($q) => $q->select('id', 'customer_id', 'return_date', 'net_amount')
                    ->latest('return_date')->limit(20),
                'area',
            ])
            ->findOrFail($customerId);

        $account = $customer->getAccount();

        if (! $account) {
            return view('CRM::reports.partials.customer-ledger', [
                'customer'     => $customer,
                'transactions' => collect([]),
            ]);
        }

        $transactions = \Modules\Account\Models\Transaction::where('account_id', $account->id)
            ->select('id', 'account_id', 'date', 'balance_type', 'credit_amount', 'debit_amount', 'description')
            ->latest('date')
            ->latest('id')
            ->limit(50)
            ->get();

        return view('CRM::reports.partials.customer-ledger', compact('customer', 'transactions'));
    }

    // =========================================================================
    // CACHE INVALIDATION  (call from observers / event listeners)
    // =========================================================================

    /**
     * Bust every report cache entry.
     * Attach this to SalesOrder / Transaction saved/deleted observers so the
     * report never shows stale numbers after a data change.
     *
     * Usage:
     *   app(CustomerBalanceReportController::class)->flushReportCache();
     */
    public function flushReportCache(): void
    {
                        // Works with any cache driver that supports tagging (Redis, Memcached).
                        // If you use the file driver, flush by prefix or switch to Redis.
        Cache::flush(); // ← replace with tag-based flush if available:
                        // Cache::tags(['customer_balance_report'])->flush();
    }
}
