<?php
namespace Modules\CRM\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\GeoLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\DailyCreditCall;
use Modules\CRM\Models\Customer\DailyLegalTask;
use Modules\CRM\Services\Customer\DailyCreditCallService;
use Modules\HRMS\Models\Employee;

class DailyCreditCallController extends Controller
{

    /**
     * Service variable
     *
     * @var DailyCreditCallService
     */
    private $service;

    public function __construct(DailyCreditCallService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Build the report data
        $reportData = $this->buildReportData($search);

        // Calculate totals
        $totals = $this->calculateTotals($reportData);

        // Only load filter data when needed (not for export)
        $filterData = $this->getFilterData();

        return view('CRM::daily-credit-call.index', [
            'reportData'     => $reportData,
            'customersearch' => $filterData['customersearch'],
            'company_info'   => $filterData['company_info'],
            'divisions'      => $filterData['divisions'],
            'districts'      => $filterData['districts'],
            'totals'         => $totals,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data             = [];
        $data['customer'] = Customer::select('id', 'company_name', 'phone', 'address')
            ->where('id', $request->id)
            ->first();

        return view('CRM::daily-credit-call.create-modal', $data);
    }

    /**
     * Show the form for creating a legal task entry.
     */
    public function legal(Request $request)
    {
        $data             = [];
        $data['customer'] = Customer::select('id', 'company_name', 'phone', 'address')
            ->where('id', $request->id)
            ->first();

        $data['legalTask'] = $this->service->legalShow($request->id);
        $data['employees'] = Employee::select(['id', 'full_name'])
            ->where('status', '1')
            ->get();

        return view('CRM::daily-credit-call.legal-modal', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'customer_id'          => 'required',
            'call_date'            => 'string',
            'commitment_date'      => 'date',
            'before_reminder_date' => 'string',
            'commitment_amount'    => 'nullable|string',
            'in_that_balance'      => 'nullable|string',
            'remarks'              => 'nullable|string',
            'status'               => 'required|string',
        ]);

        try {
            // Update previous entry status - Optimized with single query
            DailyCreditCall::where('customer_id', $validate['customer_id'])
                ->where('status', 'pending')
                ->update(['status' => 'changed']);

            $result = $this->service->store($validate);
            return redirect()->route('crm.daily-credit-calls.index')
                ->with('success', 'Daily Credit Call created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function legalStore(Request $request)
    {
        $validate = $request->validate([
            'customer_id'    => 'required',
            'task_type'      => 'string',
            'status'         => 'string',
            'assign_to'      => 'string',
            'assign_remarks' => 'string',
        ]);

        try {
            // Update previous entry status - Optimized with single query
            DailyLegalTask::where('customer_id', $validate['customer_id'])
                ->where('status', 'pending')
                ->update(['status' => 'changed']);

            $result = $this->service->legalStore($validate);
            return redirect()->route('crm.daily-credit-calls.index')
                ->with('success', 'Legal task assign successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Get customer basic info with eager loading
        $data['customer'] = Customer::select('id', 'company_name', 'phone', 'address')
            ->with(['userRef']) // ← Eager load for user reference
            ->where('id', $id)
            ->first();

        $data['dailyCreditCall'] = $this->service->show($id);

        return view("CRM::daily-credit-call.show-modal", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyCreditCall $dailyCreditCall)
    {
        $data['dailyCreditCall'] = $dailyCreditCall->load(['customer']); // ← Eager load
        return view("CRM::daily-credit-call.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyCreditCall $dailyCreditCall)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($dailyCreditCall, $validate);

        return redirect()->route('daily-credit-call.index')
            ->with('success', 'Daily Credit Call updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyCreditCall $dailyCreditCall)
    {
        $this->service->delete($dailyCreditCall);
        return redirect()->route('daily-credit-call.index')
            ->with('success', 'Daily Credit Call deleted successfully.');
    }

    /**
     * Get filter dropdown data - Optimized
     */
    private function getFilterData()
    {
        // Cache company info as it rarely changes
        $companyInfo = Cache::remember('company_info', 3600, function () {
            return CompanyInfo::first();
        });

        // Cache customers for filter
        $customers = Cache::remember('active_customers_for_credit_call', 1800, function () {
            return Customer::actived()
                ->select('id', 'company_name', 'company_place_id')
                ->with(['area']) // ← Eager load area
                ->orderBy('company_name')
                ->get();
        });

        // Cache divisions and districts
        $divisions = Cache::remember('geo_divisions', 86400, function () {
            return GeoLocation::where('type', 'Division')->get();
        });

        $districts = Cache::remember('geo_districts', 86400, function () {
            return GeoLocation::where('type', 'District')->get();
        });

        return [
            'customersearch' => $customers,
            'company_info'   => $companyInfo,
            'divisions'      => $divisions,
            'districts'      => $districts,
        ];
    }

    /**
     * Build report data - Optimized with single query
     */
    private function buildReportData($search)
    {
        // Get customer IDs first (lightweight query)
        $customerIds = $this->getFilteredCustomerIds($search);

        if (empty($customerIds)) {
            return collect([]);
        }

        // Pre-fetch all data with optimized queries
        $aggregatedData = $this->fetchAggregatedData($customerIds);

        // Get customer basic info with eager loading - Single query
        $customers = Customer::whereIn('id', $customerIds)
            ->select('id', 'company_name', 'phone', 'address', 'user_ref_id')
            ->with(['userRef']) // ← Eager load userRef
            ->get()
            ->keyBy('id');

        // Build report data
        $reportData = [];

        foreach ($customerIds as $customerId) {
            $customer = $customers[$customerId] ?? null;
            if (! $customer) {
                continue;
            }

            $openingBalance = $aggregatedData['opening_balances'][$customerId] ?? 0;

            // Skip if opening balance is 0 or negative
            if ($openingBalance <= 0) {
                continue;
            }

            $account = $customer->getAccount();

            $customerData = [
                'customer_id'     => $customerId,
                'account_id'      => $account->id ?? null,
                'customer_name'   => $customer->company_name,
                'address'         => $customer->address,
                'phone'           => $customer->phone,
                'user_reference'  => $customer->userRef->full_name ?? null,
                'opening_balance' => $openingBalance,
            ];

            $reportData[] = $customerData;
        }

        return collect($reportData);
    }

    /**
     * Get filtered customer IDs - Optimized
     */
    private function getFilteredCustomerIds($search)
    {
        $query = Customer::actived()->select('id');

        if (request()->filled('division_id')) {
            $query->whereHas('area', function ($q) {
                $q->where('division_id', request()->division_id);
            });
        }

        if (request()->filled('district_id')) {
            $query->whereHas('area', function ($q) {
                $q->where('district_id', request()->district_id);
            });
        }

        // Apply search filter
        if ($search) {
            $query->where('id', $search);
        }

        return $query->pluck('id')->toArray();
    }

    /**
     * Calculate totals - Optimized
     */
    private function calculateTotals($reportData)
    {
        return [
            'total_opening_balance' => $reportData->sum('opening_balance'),
            'total_sales'           => $reportData->sum('sales'),
            'total_sales_return'    => $reportData->sum('sales_return'),
            'total_collection'      => $reportData->sum('collection'),
            'total_due'             => $reportData->sum('due'),
            'total_closing_balance' => $reportData->sum('closing_balance'),
        ];
    }

    /**
     * Fetch aggregated data - Optimized with single queries
     */
    private function fetchAggregatedData($customerIds)
    {
        // Fetch machine codes existence in single query
        $machineCodes = DB::table('u_s_g_or_o_p_g_license_requisitions')
            ->whereIn('customer_id', $customerIds)
            ->whereNull('deleted_at')
            ->groupBy('customer_id')
            ->pluck('customer_id')
            ->flip()
            ->toArray();

        // Fetch all data in single query using UNION or multiple queries with IN
        // Opening Sales
        $openingSales = DB::table('sales_orders')
            ->whereIn('customer_id', $customerIds)
            ->whereIn('status', ['delivered', 'partial', 'approved'])
            ->whereNull('deleted_at')
            ->groupBy('customer_id')
            ->selectRaw('customer_id, SUM(net_amount) as total')
            ->pluck('total', 'customer_id')
            ->toArray();

        // Opening Returns
        $openingReturns = DB::table('sales_returns')
            ->whereIn('customer_id', $customerIds)
            ->whereNull('deleted_at')
            ->groupBy('customer_id')
            ->selectRaw('customer_id, SUM(net_amount) as total')
            ->pluck('total', 'customer_id')
            ->toArray();

        // Period Sales
        $periodSales = DB::table('sales_orders')
            ->whereIn('customer_id', $customerIds)
            ->whereIn('status', ['delivered', 'partial', 'approved'])
            ->whereNull('deleted_at')
            ->groupBy('customer_id')
            ->selectRaw('customer_id, SUM(net_amount) as total')
            ->pluck('total', 'customer_id')
            ->toArray();

        // Period Returns
        $periodReturns = DB::table('sales_returns')
            ->whereIn('customer_id', $customerIds)
            ->whereNull('deleted_at')
            ->groupBy('customer_id')
            ->selectRaw('customer_id, SUM(net_amount) as total')
            ->pluck('total', 'customer_id')
            ->toArray();

        // Customer opening balances
        $openingBalancesBefore_2021_10_05 = DB::table('customer_settings')
            ->whereIn('customer_id', $customerIds)
            ->pluck('opening_balance', 'customer_id')
            ->toArray();

        // Fetch collections
        $openingCollections = $this->fetchBulkCollections($customerIds);
        $periodCollections  = $this->fetchBulkCollections($customerIds);

        // Calculate opening balances
        $openingBalances = [];
        foreach ($customerIds as $customerId) {
            $sales                      = $openingSales[$customerId] ?? 0;
            $returns                    = $openingReturns[$customerId] ?? 0;
            $collections                = $openingCollections[$customerId] ?? 0;
            $openingBalances_2021_10_05 = $openingBalancesBefore_2021_10_05[$customerId] ?? 0;

            $openingBalances[$customerId] = $openingBalances_2021_10_05 + $sales - $returns - $collections;
        }

        return [
            'machine_codes'      => $machineCodes,
            'opening_balances'   => $openingBalances,
            'period_sales'       => $periodSales,
            'period_returns'     => $periodReturns,
            'period_collections' => $periodCollections,
        ];
    }

    /**
     * Fetch bulk collections - Optimized with eager loading
     */
    private function fetchBulkCollections($customerIds)
    {
        // Get customers and build account mapping using getAccount() method
        $customers = Customer::whereIn('id', $customerIds)
            ->with('accounts') // ← Eager load accounts
            ->get();

        $accountIds         = [];
        $customerAccountMap = [];

        foreach ($customers as $customer) {
            $account = $customer->getAccount();
            if ($account) {
                $accountIds[]                     = $account->id;
                $customerAccountMap[$account->id] = $customer->id;
            }
        }

        if (empty($accountIds)) {
            return [];
        }

        // Fetch all transactions in one query
        $collections = DB::table('transactions')
            ->whereIn('account_id', $accountIds)
            ->where('balance_type', 'credit')
            ->whereNull('deleted_at')
            ->groupBy('account_id')
            ->selectRaw('account_id, SUM(credit_amount) as total')
            ->pluck('total', 'account_id')
            ->toArray();

        // Map back to customer IDs
        $result = [];
        foreach ($collections as $accountId => $amount) {
            if (isset($customerAccountMap[$accountId])) {
                $result[$customerAccountMap[$accountId]] = $amount;
            }
        }

        return $result;
    }
}
