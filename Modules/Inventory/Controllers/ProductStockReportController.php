<?php

namespace Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Stock;
use Modules\Inventory\Services\ExportService;
use Modules\Inventory\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStockReportController extends Controller
{
    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

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
        return view('Inventory::reports.product-stock-report.product-stock-report', [
            'reportData' => $reportData,
            'products' => $filterData['products'],
            'branches' => $filterData['branches'],
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
        $query = DB::table('stocks as s')
            ->select([
                'b.name as branch_name',
                'p.id as product_id',
                'p.name as product_name',
                'p.model',
                DB::raw('SUM(CASE WHEN s.stock_type = "in" THEN s.in_qty ELSE 0 END) as total_received'),
                DB::raw('SUM(CASE WHEN s.stock_type = "out" THEN s.out_qty ELSE 0 END) as total_delivered'),
                DB::raw('SUM(CASE WHEN s.stock_type = "in" THEN s.in_qty ELSE 0 END) - SUM(CASE WHEN s.stock_type = "out" THEN s.out_qty ELSE 0 END) as current_stock'),
                DB::raw(' SUM(CASE WHEN s.stock_type = "in" THEN s.in_qty ELSE 0 END) - SUM(CASE WHEN s.stock_type = "out" THEN s.out_qty ELSE 0 END) as physical_stock')
            ])
            ->join('branches as b', 's.branch_id', '=', 'b.id')
            ->join('product_catalogs as p', 's.product_id', '=', 'p.id')
            ->groupBy('b.id', 'b.name', 'p.id', 'p.name', 'p.model');

        // Apply filters
        // $this->applyFilters($query, $request);
        // Product filter
        if ($request->filled('product_id')) {
            $query->where('p.id', $request->product_id);
        }

        // Branch filter
        if ($request->filled('branch_id')) {
            $query->where('b.id', $request->branch_id);
        }

        // Date range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('s.date', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59'
            ]);
        } elseif ($request->filled('from')) {
            $query->where('s.date', '>=', $request->from . ' 00:00:00');
        } elseif ($request->filled('to')) {
            $query->where('s.date', '<=', $request->to . ' 23:59:59');
        }

        // Order by
        $query->orderBy('b.name')->orderBy('p.name');

        return $query;
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, $request)
    {
        // Product filter
        if ($request->filled('product_id')) {
            $query->where('p.id', $request->product_id);
        }

        // Branch filter
        if ($request->filled('branch_id')) {
            $query->where('b.id', $request->branch_id);
        }

        // Date range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('s.created_at', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59'
            ]);
        } elseif ($request->filled('from')) {
            $query->where('s.created_at', '>=', $request->from . ' 00:00:00');
        } elseif ($request->filled('to')) {
            $query->where('s.created_at', '<=', $request->to . ' 23:59:59');
        }

        return $query;
    }

    /**
     * Calculate average and last prices with amounts
     */
    private function calculateTotals($reportData)
    {
        $totalStock = 0;
        $totalPhysicalStock = 0;
        $totalAvgAmount = 0;
        $totalLastAmount = 0;

        foreach ($reportData as $item) {
            // Get pricing data
            $avgPrice = $this->getAveragePrice($item->product_id, 5);
            $lastPrice = $this->getLastPrice($item->product_id);

            // Calculate amounts
            $avgAmount = $item->current_stock * $avgPrice;
            $lastAmount = $item->current_stock * $lastPrice;

            // Add to item
            $item->avg_price = $avgPrice;
            $item->last_price = $lastPrice;
            $item->avg_amount = $avgAmount;
            $item->last_amount = $lastAmount;

            // Sum totals
            $totalStock += $item->current_stock;
            $totalPhysicalStock += $item->physical_stock;
            $totalAvgAmount += $avgAmount;
            $totalLastAmount += $lastAmount;
        }

        return [
            'total_stock' => $totalStock,
            'total_physical_stock' => $totalPhysicalStock,
            'total_avg_amount' => $totalAvgAmount,
            'total_last_amount' => $totalLastAmount
        ];
    }

    /**
     * Get average price from last N sales
     */
    private function getAveragePrice($productId, $limit = 5)
    {
        $avgPrice = DB::table('sales_order_details')
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->avg('price');

        return round($avgPrice ?? 0, 2);
    }

    /**
     * Get last sale price
     */
    private function getLastPrice($productId)
    {
        $lastPrice = DB::table('sales_order_details')
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->value('price');

        return round($lastPrice ?? 0, 2);
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

        $filename = 'Product_Stock_Report_' . now()->format('Y_m_d_His');

        return (new ExportService())->exportData(
            $data,
            'Inventory::reports.product-stock-report.export.',
            $filename,
            $exportType
        );
    }
}