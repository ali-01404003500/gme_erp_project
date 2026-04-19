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
use Carbon\Carbon;
use Modules\Inventory\Models\Product\Settings\Brand;

class CenterWiseStockReportController extends Controller
{
    public function index(Request $request)
    {
        $filterData = $this->getFilterData();
        
        // Build query for report
        $reportData = $this->buildReportQuery($request);
        
        // Handle export
        if ($request->filled('export_type')) {
            return $this->exportReport($reportData, $filterData, $request->export_type, $request);
        }

        return view('Inventory::reports.center-stock.index', [
            'reportData' => $reportData,
            'products' => $filterData['products'],
            'branches' => $filterData['branches'],
            'brands' => $filterData['brands'],
            'company_info' => $filterData['company_info'],
            'filters' => $request->all()
        ]);
    }

    private function buildReportQuery($request)
    {
        $defaultDate = Carbon::parse('2026-04-18')->startOfDay();
        $fromDate = $request->from ? Carbon::parse($request->from)->startOfDay() : null;
        // if (!$fromDate || $fromDate->lt($defaultDate)) {
        //     $fromDate = $defaultDate;
          
        // } 
        $toDate = $request->to ? Carbon::parse($request->to)->endOfDay() : Carbon::now()->endOfDay();
        
        $query = Stock::withoutGlobalScope('latest')
        ->with(['product', 'branch'])
        ->select([
            'product_id',
            'branch_id',
            DB::raw('SUM(CASE WHEN stock_type = "in" THEN in_qty ELSE 0 END) as total_in'),
            DB::raw('SUM(CASE WHEN stock_type = "out" THEN out_qty ELSE 0 END) as total_out')
        ])
        ->groupBy('product_id', 'branch_id');


        // Apply filters
        if ($request->filled('branch_id')) {

            if ($request->branch_id == 'all') {
                $query->where('branch_id', '!=', 4); //  branch_id 4 বাদ
            } else {
                $query->where('branch_id', $request->branch_id);
            }

        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('brand')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('product_brand_id', $request->brand);
            });
        }

        if ($fromDate || $toDate) {
            if ($fromDate) {
                $query->where('date', '>=', $fromDate);
            }
            if ($toDate) {
                $query->where('date', '<=', $toDate);
            }
        }

        $stocks = $query->get();

        // Calculate opening stock and format data
        $reportData = collect();
        foreach ($stocks as $stock) {
            $openingStock = $this->getOpeningStock($stock->product_id, $stock->branch_id, $fromDate);
            
            $currentStock = $openingStock + $stock->total_in - $stock->total_out;
            
            $reportData->push((object)[
                'product' => $stock->product,
                'branch' => $stock->branch,
                'opening_stock' => $openingStock,
                'received' => $stock->total_in,
                'delivered' => $stock->total_out,
                'current_stock' => $currentStock,
                'product_id' => $stock->product_id,
                'branch_id' => $stock->branch_id
            ]);
        }

        return $reportData;
    }

    private function getOpeningStock($productId, $branchId, $fromDate)
    {
        if (!$fromDate) {
            return 0;
        }

        $openingDate = Carbon::parse($fromDate)->subDay()->endOfDay();

        return Stock::where('product_id', $productId)
            ->where('branch_id', $branchId)
            ->where('date', '>=', $openingDate)
            ->where('created_at', '<=', $openingDate)
            ->sum(DB::raw('CASE WHEN stock_type = "in" THEN in_qty ELSE -out_qty END'));
    }

    public function productLedger(Request $request, $productId)
    {
        $branchId = $request->branch_id;
        $fromDate = $request->from ? Carbon::parse($request->from)->startOfDay() : null;
        $toDate = $request->to ? Carbon::parse($request->to)->endOfDay() : Carbon::now()->endOfDay();

        $product = ProductCatalog::findOrFail($productId);
        
        // Get opening stock
        $openingStock = $this->getOpeningStock($productId, $branchId, $fromDate);

        // Get transactions
        $transactions = Stock::with(['source', 'branch'])
            ->where('product_id', $productId)
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($fromDate, function($q) use ($fromDate) {
                $q->where('date', '>=', $fromDate);
            })
            ->when($toDate, function($q) use ($toDate) {
                $q->where('date', '<=', $toDate);
            })
            ->orderBy('date', 'asc')
            ->get();

        $runningStock = $openingStock;
        $ledgerData = collect();

        // Add opening stock row
        if ($fromDate) {
            $ledgerData->push((object)[
                'date' => Carbon::parse($fromDate)->subDay()->format('d-M-Y'),
                'reference' => 'Opening Stock',
                'status' => '-',
                'activity_by' => '-',
                'checked_by' => '-',
                'received' => 0,
                'delivered' => 0,
                'stock' => $openingStock,
                'is_opening' => true
            ]);
        }

        foreach ($transactions as $transaction) {
            $runningStock += ($transaction->stock_type == 'in' ? $transaction->in_qty : -$transaction->out_qty);
            $ledgerData->push((object)[
                'date' => Carbon::parse($transaction->date)->format('d-M-Y h:i A'),
                'reference' => $transaction->source_name,
                'reference_id' => $transaction->source_id,
                'source_type' => $transaction->source_type,
                'status' => $transaction->status ?? 'Completed',
                'activity_by' => $transaction->createdBy->name ?? '-',
                'checked_by' => $transaction->checked_by_user->name ?? '-',
                'received' => $transaction->stock_type == 'in' ? $transaction->in_qty : 0,
                'delivered' => $transaction->stock_type == 'out' ? $transaction->out_qty : 0,
                'stock' => $runningStock,
                'is_opening' => false
            ]);
        }

        return view('Inventory::reports.center-stock.product-ledger', [
            'product' => $product,
            'ledgerData' => $ledgerData,
            'openingStock' => $openingStock,
            'totalReceived' => $ledgerData->sum('received'),
            'totalDelivered' => $ledgerData->sum('delivered'),
            'closingStock' => $runningStock,
            'company_info' => CompanyInfo::first()

        ]);
    }

    public function centerStockDetail(Request $request, $productId)
    {
        $defaultDate = Carbon::parse('2026-04-18')->startOfDay();
        $fromDate =  $request->from ? Carbon::parse($request->from)->startOfDay() : null; 
        // if (!$fromDate || $fromDate->lt($defaultDate)) {
        //     $fromDate = $defaultDate;
          
        // }
        $toDate = $request->to ? Carbon::parse($request->to)->endOfDay() : Carbon::now()->endOfDay();
        $branchId = $request->branch_id;

        $product = ProductCatalog::findOrFail($productId);
        
        $centerStocks = Stock::withoutGlobalScope('latest')->with('branch')
            ->select([
                'branch_id',
                DB::raw('SUM(CASE WHEN stock_type = "in" THEN in_qty ELSE 0 END) as total_in'),
                DB::raw('SUM(CASE WHEN stock_type = "out" THEN out_qty ELSE 0 END) as total_out')
            ])
            ->where('product_id', $productId)
            ->when($fromDate, function($q) use ($fromDate) {
                $q->where('date', '>=', $fromDate);
            })
            ->when($toDate, function($q) use ($toDate) {
                $q->where('date', '<=', $toDate);
            })
            ->when($branchId && $branchId !== 'all', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })


            ->groupBy('branch_id')
            ->get();

        $centerData = collect();
        foreach ($centerStocks as $stock) {
            $openingStock = $this->getOpeningStock($productId, $stock->branch_id, $fromDate);
            $centerData->push((object)[
                'branch_name' => $stock->branch->name,
                'stock' => $openingStock + $stock->total_in - $stock->total_out
            ]);
        }

        return view('Inventory::reports.center-stock.center-detail', [
            'product' => $product,
            'centerData' => $centerData,
            'totalStock' => $centerData->sum('stock'),
            'company_info' => CompanyInfo::first()
        ]);
    }


    public function expiredInfo(Request $request, $productId)
{
    $branchId = $request->branch_id;
    $product = ProductCatalog::findOrFail($productId);
    
    // Get date range if provided
    $fromDate = $request->from ? Carbon::parse($request->from)->startOfDay() : null;
    $toDate = $request->to ? Carbon::parse($request->to)->endOfDay() : Carbon::now()->endOfDay();
    
    // Calculate opening stock (before fromDate)
    $openingStock = 0;
    if ($fromDate) {
        $openingStock = $this->getOpeningStock($productId, $branchId, $fromDate);
    }
    
    if ($product->is_serial == 'yes') {
        $expiredData = Stock::withoutGlobalScope('latest')
            ->where('product_id', $productId)
            ->when($branchId, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->with('source')
            ->get()
            ->groupBy('serial_no')
            ->map(function ($lotGroup) {
                $firstItem = $lotGroup->first();
                return (object) [
                    'serial_no' => $firstItem->serial_no,
                    'source' => $firstItem->source,
                    'in_qty' => $lotGroup->sum('in_qty'),
                    'out_qty' => $lotGroup->sum('out_qty'),
                    'current_stock' => $lotGroup->sum('in_qty') - $lotGroup->sum('out_qty'),
                ];
            })
            ->values();
    } else {
        $expiredData = Stock::withoutGlobalScope('latest')
            ->where('product_id', $productId)
            ->when($branchId, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->with('source')
            ->get()
            ->groupBy('lot_no')
            ->map(function ($lotGroup) {
                $firstItem = $lotGroup->first();
                return (object) [
                    'lot_no' => $firstItem->lot_no,
                    'source' => $firstItem->source,
                    'in_qty' => $lotGroup->sum('in_qty'),
                    'out_qty' => $lotGroup->sum('out_qty'),
                    'current_stock' => $lotGroup->sum('in_qty') - $lotGroup->sum('out_qty'),
                ];
            })
            ->values();
    }
    
    // Calculate closing stock
    $closingStock = $openingStock + $expiredData->sum('in_qty') - $expiredData->sum('out_qty');
    
    return view('Inventory::reports.center-stock.expired-info', [
        'product'      => $product,
        'expiredData'  => $expiredData,
        'openingStock' => $openingStock,
        'closingStock' => $closingStock,
        'totalStock'   => $expiredData->sum('in_qty') - $expiredData->sum('out_qty'),
        'company_info' => CompanyInfo::first()
    ]);
}


    private function getFilterData()
    {
        return [
            'products' => ProductCatalog::where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'model', 'product_brand_id']),
            'branches' => Branch::orderBy('name')->get(['id', 'name']),
            'brands' => Brand::orderBy('name')->get(['id', 'name']),
            'company_info' => CompanyInfo::first()
        ];
    }

    private function exportReport($reportData, $filterData, $exportType, $request)
    {
        $data = array_merge([
            'reportData' => $reportData,
            'filters' => $request->all()
        ], $filterData);

        $filename = 'Center_Stock_Report_' . now()->format('Y_m_d_His');

        return (new ExportService())->exportData(
            $data,
            'Inventory::reports.center-stock.export.',
            $filename,
            $exportType
        );
    }
}