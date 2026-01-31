<?php

namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo as AccessControlCompanyInfo;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Inventory\Models\Settings\Tag;
use Modules\Sales\Models\SalesOrder;

class BrandSupplierSalesReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter data
        $brands = Brand::orderBy('name')->get();
        $productTags = Tag::orderBy('name')->get();
        
        // Initialize report data
        $reportData = [];
        
        // If filters are applied, generate report
        if ($request->filled(['brand_id', 'from', 'to'])) {
            $reportData = $this->generateReport($request);
            // dd($reportData);
            
            // Handle export types
            if ($request->filled('export_type')) {
                return $this->exportReport($request, $reportData);
            }
        }
        
        return view("Sales::reports.brand-supplier-sales-report.index", compact(
            'brands',
            'productTags',
            'reportData'
        ));
    }
    
    private function generateReport(Request $request)
    {
        $brandId = $request->brand_id;
        $productTagId = $request->product_tag_id;
        $topRange = $request->top_range ?? 'all';
        $fromDate = $request->from;
        $toDate = $request->to;
        
        // Get brand name
        $brand = Brand::find($brandId);
        $isDrawray = strtolower($brand->name) === 'drawray' || strtolower($brand->name) === 'DRAWRAY';
        
        // Build query for sales orders with delivered/partial status
        $query = SalesOrder::with([
            'customer',
            'salesOrderDetails.product.brand',
            'salesOrderDetails.product.tag'
        ])
        ->whereIn('status', ['delivered', 'partial'])
        ->whereBetween('invoice_date', [$fromDate, $toDate])
        ->whereHas('salesOrderDetails.product', function($q) use ($brandId) {
            $q->where('product_brand_id', $brandId);
        });
        
        // Apply product tag filter if specified
        if ($productTagId) {
            $query->whereHas('salesOrderDetails.product.tag', function($q) use ($productTagId) {
                $q->where('product_tag_id', $productTagId);
            });
        }
        
        $salesOrders = $query->get();
        // dd($salesOrders);
        
        // Group data by customer
        $customerData = [];
        
        foreach ($salesOrders as $order) {
            $customerId = $order->customer_id;
            
            if (!isset($customerData[$customerId])) {
                $customerData[$customerId] = [
                    'customer_id' => $customerId,
                    'customer_name' => optional($order->customer)->company_name ?? 'N/A',
                    'customer_address' => optional($order->customer)->address ?? 'N/A',
                    'customer_phone' => optional($order->customer)->phone ?? 'N/A',
                    'brand_name' => $brand->name,
                    'products' => [],
                    'total_amount' => 0
                ];
            }
            
            // Process each order detail
            foreach ($order->salesOrderDetails as $detail) {
                // dd($detail);
                // Check if product belongs to the selected brand
                if ($detail->product->product_brand_id != $brandId) {
                    continue;
                }
                
                // Check product tag filter
                if ($productTagId) {
                    $hasTag = $detail->product->tag->where('id', $productTagId);
                    if (!$hasTag) {
                        continue;
                    }
                }
                
                $productId = $detail->product_id;
                $productName = $detail->product->name;
                $unitType = $detail->product->unit->name ?? 'Unit';
                
                // Initialize product if not exists
                if (!isset($customerData[$customerId]['products'][$productId])) {
                    $customerData[$customerId]['products'][$productId] = [
                        'product_id' => $productId,
                        'product_name' => $productName,
                        'unit_type' => $unitType,
                        'quantity' => 0,
                        'total_price' => 0
                    ];
                }
                
                // Add quantities and amounts
                $customerData[$customerId]['products'][$productId]['quantity'] += $detail->quantity;
                $customerData[$customerId]['products'][$productId]['total_price'] += $detail->amount;
                $customerData[$customerId]['total_amount'] += $detail->amount;

            }
        }
        
        // Convert products array to indexed array
        foreach ($customerData as &$customer) {
            $customer['products'] = array_values($customer['products']);
        }
        
        // Sort customers by total amount (descending)
        usort($customerData, function($a, $b) {
            return $b['total_amount'] <=> $a['total_amount'];
        });
        
        // Apply top range filter
        if ($topRange !== 'all' && is_numeric($topRange)) {
            $customerData = array_slice($customerData, 0, (int)$topRange);
        }
        
        return $customerData;
    }
    
    private function exportReport(Request $request, $reportData)
    {
        $exportType = $request->export_type;
        $company_info = AccessControlCompanyInfo::first();
        
        // Get filter information
        $brand = Brand::find($request->brand_id);
        $brandName = $brand->name;
        $productTagName = $request->product_tag_id 
            ? Tag::find($request->product_tag_id)->name 
            : 'All';
        $topRange = $request->top_range === 'all' 
            ? 'ALL' 
            : 'Top ' . $request->top_range;
        $fromDate = $request->from;
        $toDate = $request->to;
        
        if ($exportType === 'pdf') {
            $pdf = PDF::loadView('Sales::reports.brand-supplier-sales-report.export.pdf', compact(
                'reportData',
                'company_info',
                'brandName',
                'productTagName',
                'topRange',
                'fromDate',
                'toDate'
            ));
            
            $pdf->setPaper('a4', 'landscape');
            return $pdf->stream('brand-supplier-sales-report.pdf');
        }
        
        if ($exportType === 'excel') {
            return Excel::download(
                new class($reportData, $company_info, $brandName, $productTagName, $topRange, $fromDate, $toDate) implements \Maatwebsite\Excel\Concerns\FromView {
                    private $reportData;
                    private $company_info;
                    private $brandName;
                    private $productTagName;
                    private $topRange;
                    private $fromDate;
                    private $toDate;
                    
                    public function __construct($reportData, $company_info, $brandName, $productTagName, $topRange, $fromDate, $toDate)
                    {
                        $this->reportData = $reportData;
                        $this->company_info = $company_info;
                        $this->brandName = $brandName;
                        $this->productTagName = $productTagName;
                        $this->topRange = $topRange;
                        $this->fromDate = $fromDate;
                        $this->toDate = $toDate;
                    }
                    
                    public function view(): \Illuminate\Contracts\View\View
                    {
                        return view('Sales::reports.brand-supplier-sales-report.export.excel', [
                            'reportData' => $this->reportData,
                            'company_info' => $this->company_info,
                            'brandName' => $this->brandName,
                            'productTagName' => $this->productTagName,
                            'topRange' => $this->topRange,
                            'fromDate' => $this->fromDate,
                            'toDate' => $this->toDate,
                        ]);
                    }
                },
                'brand-supplier-sales-report.xlsx'
            );
        }
    }
}