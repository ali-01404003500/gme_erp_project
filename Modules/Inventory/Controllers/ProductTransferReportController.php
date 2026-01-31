<?php

namespace Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use App\Models\User;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\ProductTransfer;
use Modules\Inventory\Models\ProductTransferDetail;
use Modules\Inventory\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductTransferReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter data
        $filterData = $this->getFilterData();

        // Build query
        $query = $this->buildReportQuery($request);

        // Get report data
        $reportData = $query->get();

        // Calculate totals
        $totals = $this->calculateTotals($reportData);

        // Handle export
        if ($request->filled('export_type')) {
            return $this->exportReport($reportData, $filterData, $totals, $request->export_type, $request);
        }

        // Return view with data
        return view('Inventory::reports.product-transfer.index', [
            'reportData' => $reportData,
            'products' => $filterData['products'],
            'branches' => $filterData['branches'],
            'users' => $filterData['users'],
            'company_info' => $filterData['company_info'],
            'totals' => $totals,
            'filters' => $request->all()
        ]);
    }

    /**
     * Build the report query based on filters
     */
    private function buildReportQuery($request)
    {
        $query = DB::table('product_transfer_details as ptd')
            ->select([
                'ptd.id',
                'pt.id as transfer_id',
                'pt.invoice_no',
                'pt.transfer_date',
                'pt.created_at as inv_date_time',
                'ptd.quantity',
                'pc.name as product_name',
                'pc.model as product_model',
                'source_branch.name as source_branch_name',
                'dest_branch.name as destination_branch_name',
                'transfer_by.name as transferred_by_name',
                'received_by.name as received_by_name',
                'requested_by.name as requested_by_name',
                'ptr.status as transfer_status',
                'pt.source_warehouse_id',
                'pt.destination_warehouse_id'
            ])
            ->join('product_transfers as pt', 'ptd.product_transfer_id', '=', 'pt.id')
            ->join('product_catalogs as pc', 'ptd.product_id', '=', 'pc.id')
            ->join('branches as source_branch', 'pt.source_warehouse_id', '=', 'source_branch.id')
            ->join('branches as dest_branch', 'pt.destination_warehouse_id', '=', 'dest_branch.id')
            ->leftJoin('product_transfer_requests as ptr', 'pt.product_transfer_request_id', '=', 'ptr.id')
            ->leftJoin('users as transfer_by', 'pt.created_by', '=', 'transfer_by.id')
            ->leftJoin('users as received_by', 'pt.updated_by', '=', 'received_by.id')
            ->leftJoin('users as requested_by', 'ptr.created_by', '=', 'requested_by.id')
            ->whereNull('pt.deleted_at');

        // Apply filters
        $this->applyFilters($query, $request);

        // Order by
        $query->orderBy('pt.created_at', 'desc');

        return $query;
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, $request)
    {
        // Product filter
        if ($request->filled('product_id')) {
            $query->where('pc.id', $request->product_id);
        }

        // User filter (transfer by, received by, or requested by)
        if ($request->filled('user_id')) {
            $userId = $request->user_id;
            $query->where(function($q) use ($userId) {
                $q->where('pt.created_by', $userId)
                  ->orWhere('pt.updated_by', $userId)
                  ->orWhere('ptr.created_by', $userId);
            });
        }

        // Branch filter
        if ($request->filled('branch_id')) {
            $branchId = $request->branch_id;
            $query->where(function($q) use ($branchId) {
                $q->where('pt.source_warehouse_id', $branchId)
                  ->orWhere('pt.destination_warehouse_id', $branchId);
            });
        }

        // Date range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('pt.transfer_date', [
                $request->from,
                $request->to
            ]);
        } elseif ($request->filled('from')) {
            $query->where('pt.transfer_date', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->where('pt.transfer_date', '<=', $request->to);
        }

        // Stock type filter (In/Out based on context)
        if ($request->filled('stock_type') && $request->stock_type != 'all') {
            if ($request->stock_type == 'in') {
                // Show transfers where selected branch is destination
                if ($request->filled('branch_id')) {
                    $query->where('pt.destination_warehouse_id', $request->branch_id);
                }
            } elseif ($request->stock_type == 'out') {
                // Show transfers where selected branch is source
                if ($request->filled('branch_id')) {
                    $query->where('pt.source_warehouse_id', $request->branch_id);
                }
            }
        }

        // Transfer type filter
        if ($request->filled('transfer_type') && $request->transfer_type != 'all') {
            switch ($request->transfer_type) {
                case 'transfer_by':
                    $query->whereNotNull('pt.created_by');
                    break;
                case 'received_by':
                    $query->whereNotNull('pt.updated_by');
                    break;
                case 'request_by':
                    $query->whereNotNull('ptr.created_by');
                    break;
            }
        }

        // Transfer status filter
        if ($request->filled('transfer_status') && $request->transfer_status != 'all') {
            $query->where('ptr.status', $request->transfer_status);
        }

        return $query;
    }

    /**
     * Calculate totals
     */
    private function calculateTotals($reportData)
    {
        $totalQuantity = $reportData->sum('quantity');

        return [
            'total_quantity' => $totalQuantity,
            'total_records' => $reportData->count()
        ];
    }

    /**
     * Get filter dropdown data
     */
    private function getFilterData()
    {
        return [
            'products' => ProductCatalog::where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'model']),
            'branches' => Branch::orderBy('name')
                ->get(['id', 'name']),
            'users' => User::orderBy('name')
                ->get(['id', 'name', 'email']),
            'company_info' => CompanyInfo::first()
        ];
    }

    /**
     * Export report to PDF or Excel
     */
    private function exportReport($reportData, $filterData, $totals, $exportType, $request)
    {
        $data = array_merge([
            'reportData' => $reportData,
            'totals' => $totals,
            'filters' => $request->all()
        ], $filterData);

        $filename = 'Product_Transfer_Report_' . now()->format('Y_m_d_His');

        return (new ExportService())->exportData(
            $data,
            'Inventory::reports.product-transfer.export.',
            $filename,
            $exportType
        );
    }
}