<?php

namespace Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Stock;
use Modules\Inventory\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockBalanceReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter data
        $filterData = $this->getFilterData();

        // Build query
        $query = $this->buildReportQuery($request);

        // Get report data
        $reportData = $query->get();

        // Calculate prices and amounts for each product
        $this->calculateProductValues($reportData);

        // Calculate totals
        $totals = $this->calculateTotals($reportData);

        // Handle export
        if ($request->filled('export_type')) {
            return $this->exportReport($reportData, $filterData, $totals, $request->export_type, $request);
        }

        // Return view with data
        return view('Inventory::reports.stock-balance-report.index', [
            'reportData' => $reportData,
            'products' => $filterData['products'],
            'brands' => $filterData['brands'],
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
    // Build branch filter condition for stock subquery
    $branchCondition = '';
    if ($request->filled('branch_id') && $request->branch_id != 'all') {
        $branchId = (int) $request->branch_id;
        $branchCondition = " AND s.branch_id = {$branchId} ";
    }

    $query = DB::table('product_catalogs as p')
        ->select([
            'p.id as product_id',
            'p.name as product_name',
            'p.model as product_model',
            'p.product_brand_id as brand_id',
            'p.mrp as mrp_price_bdt',
            'b.name as brand_name',

            DB::raw('(SELECT dollar_price FROM products WHERE product_catalog_id = p.id AND deleted_at IS NULL LIMIT 1) as unit_price_usd'),
            DB::raw('(SELECT last_cost_price FROM products WHERE product_catalog_id = p.id AND deleted_at IS NULL LIMIT 1) as costing_price_bdt'),

            DB::raw('COALESCE((
                SELECT 
                    SUM(CASE WHEN s.stock_type = "in" THEN s.in_qty ELSE 0 END) - 
                    SUM(CASE WHEN s.stock_type = "out" THEN s.out_qty ELSE 0 END)
                FROM stocks s
                WHERE s.product_id = p.id
                ' . ($request->filled('to') ? ' AND s.date <= "' . $request->to . ' 23:59:59"' : '') . '
                ' . $branchCondition . '
            ), 0) as current_stock')
        ])
        ->leftJoin('brands as b', 'p.product_brand_id', '=', 'b.id')
        ->where('p.status', 'active')
        ->whereNull('p.deleted_at');

    // Apply filters
    $this->applyFilters($query, $request);

    // Order by product name
    $query->orderBy('p.name');

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

        // Brand filter
        if ($request->filled('brand_id')) {
            $query->where('p.product_brand_id', $request->brand_id);
        }

        return $query;
    }

    /**
     * Calculate prices and amounts for each product
     */
    private function calculateProductValues($reportData)
    {
        foreach ($reportData as $item) {
            // Calculate USD totals
            $item->total_usd = $item->current_stock * ($item->unit_price_usd ?? 0);

            // Calculate BDT totals
            $item->total_mrp_bdt = $item->current_stock * ($item->mrp_price_bdt ?? 0);
            $item->total_costing_bdt = $item->current_stock * ($item->costing_price_bdt ?? 0);

            // Get average selling price from last 5 sales
            $item->avg_selling_price_bdt = $this->getAverageSellingPrice($item->product_id, 5);
            $item->total_avg_sales_bdt = $item->current_stock * $item->avg_selling_price_bdt;
        }
    }

    /**
     * Get average selling price from last 5 quantities sold
     * Sales Price = MRP - Discount
     */
    private function getAverageSellingPrice($productId, $limit = 5)
    {
        $lastSales = DB::table('sales_order_details as sod')
            ->select([
                DB::raw('(sod.price - COALESCE(sod.unit_discount, 0)) as sales_price')
            ])
            ->where('sod.product_id', $productId)
            ->orderBy('sod.created_at', 'desc')
            ->limit($limit)
            ->get();

        if ($lastSales->isEmpty()) {
            return 0;
        }

        $totalSalesPrice = $lastSales->sum('sales_price');
        $avgPrice = $totalSalesPrice / $lastSales->count();

        return round($avgPrice, 2);
    }

    /**
     * Calculate totals for summary section
     */
    private function calculateTotals($reportData)
    {
        $totalUsd = 0;
        $totalMrpBdt = 0;
        $totalCostingBdt = 0;
        $totalAvgSalesBdt = 0;

        foreach ($reportData as $item) {
            $totalUsd += $item->total_usd;
            $totalMrpBdt += $item->total_mrp_bdt;
            $totalCostingBdt += $item->total_costing_bdt;
            $totalAvgSalesBdt += $item->total_avg_sales_bdt;
        }

        return [
            'total_usd' => $totalUsd,
            'total_mrp_bdt' => $totalMrpBdt,
            'total_costing_bdt' => $totalCostingBdt,
            'total_avg_sales_bdt' => $totalAvgSalesBdt,
        ];
    }

    /**
     * Get filter dropdown data
     */
    private function getFilterData()
    {
        return [
            'products' => ProductCatalog::where('status', 'active')
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get(['id', 'name', 'model']),
            'brands' => DB::table('brands')
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get(['id', 'name']),
            'branches' => Branch::whereNull('deleted_at')
                ->orderBy('name')
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
            'filters' => $request->all(),
            'usd_to_bdt_rate' => $request->input('usd_to_bdt_rate', 110) // Default conversion rate
        ], $filterData);

        $filename = 'Stock_Balance_Report_' . now()->format('Y_m_d_His');

        return (new ExportService())->exportData(
            $data,
            'Inventory::reports.stock-balance-report.export.',
            $filename,
            $exportType
        );
    }
}