<?php

namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Broker;
use Modules\Sales\Models\SalesCommission;
use Modules\Inventory\Services\ExportService;
use Carbon\Carbon;

class BrokerCommissionReportController extends Controller
{
    public function index(Request $request)
    {
        // Build query for broker commissions
        $query = SalesCommission::with([
            'broker.brokerBank',
            'salesOrder.customer',
            'createdBy'
        ]);

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get filtered data
        $commissions = $query->orderBy('commission_date', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Prepare report data
        $reportData = collect();
        foreach ($commissions as $commission) {
            $reportData->push([
                'data' => $commission,
                'date' => $commission->commission_date,
            ]);
        }

        // Sort by date descending
        $reportData = $reportData->sortByDesc('date')->values();

        // Handle export (before pagination)
        if ($request->filled('export_type')) {
            // Get selected columns from request
            $selectedColumns = $request->filled('columns') 
                ? explode(',', $request->columns) 
                : ['broker', 'customer', 'bank', 'commission', 'date', 'type', 'status', 'reference'];

            $data = [
                'reportData' => $reportData,
                'brokers' => Broker::activeBrokers()->get(),
                'company_info' => CompanyInfo::first(),
                'selectedColumns' => $selectedColumns,
            ];
            
            $filename = 'Broker_Commission_Report_' . now()->format('Y_m_d_His');
            
            return (new ExportService())->exportData(
                $data,
                'Sales::reports.broker-commission-report.export.',
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
            'brokers' => Broker::activeBrokers()->get(),
            'company_info' => CompanyInfo::first(),
        ];

        return view('Sales::reports.broker-commission-report.index', $data);
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, $request)
    {
        // Broker filter
        if ($request->filled('broker_id')) {
            $query->where('broker_id', $request->broker_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('commission_date', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay()
            ]);
        } elseif ($request->filled('from')) {
            $query->where('commission_date', '>=', Carbon::parse($request->from)->startOfDay());
        } elseif ($request->filled('to')) {
            $query->where('commission_date', '<=', Carbon::parse($request->to)->endOfDay());
        }

        // Commission type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return $query;
    }
}