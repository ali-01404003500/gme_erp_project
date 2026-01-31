<?php

namespace Modules\Services\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\GeoLocation;
use Modules\Services\Models\ServiceToken;
use Modules\Services\Models\ServiceMyTask;
use Modules\Services\Models\EngineerAssign;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Services\Models\ServicePendingToken;
use Modules\Inventory\Services\ExportService;

class ServiceReportController extends Controller
{
    public function index(Request $request)
    {
        // Base query with all necessary relationships
        $query = ServiceToken::with([
            'service',
            'customer.account',
            'product',
            'serviceMyTask.bills',
            'serviceMyTask.returnBills',
            'serviceMyTask.pendingServiceTokens',
            'engineerAssign.engineers',
            'service.createdBy',
            'service.emergencyNotes'
        ]);

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get report data
        $reportData = $query->orderBy('token_date', 'desc')->get();

        // Get filter data for dropdowns
        $filterData = $this->getFilterData();

        // Determine report type based on filters
        $reportType = $this->getReportType($request);

        // Handle exports
        if ($request->filled('export_type')) {
            return $this->exportReport($reportData, $filterData, $reportType, $request->export_type);
        }

        // Paginate results
        $perPage = 50;
        $currentPage = $request->input('page', 1);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $reportData->forPage($currentPage, $perPage),
            $reportData->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('Services::reports.service-report', [
            'reportData' => $paginatedData,
            'customers' => $filterData['customers'],
            'products' => $filterData['products'],
            'divisions' => $filterData['divisions'],
            'districts' => $filterData['districts'],
            'company_info' => $filterData['company_info'],
            'reportType' => $reportType
        ]);
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, $request)
    {
        // Product Name filter
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Customer Name filter
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->status;
            
            switch ($status) {
                case 'Junk':
                case 'Pending':
                case 'Failed':
                    $query->where('action', $status);
                    break;
                    
                case 'Live':
                case 'Started':
                case 'Done':
                case 'Cancelled':
                    $query->whereHas('serviceMyTask', function($q) use ($status) {
                        if ($status == 'Live') {
                            $q->where('action', 'Live');
                        } else {
                            $q->where('status', strtolower($status))->orWhere('action', $status);
                        }
                    });
                    break;
            }
        }

        // Division filter
        if ($request->filled('division_id')) {
            $query->whereHas('customer.area', function($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        // District filter
        if ($request->filled('district_id')) {
            $query->whereHas('customer.area', function($q) use ($request) {
                $q->where('district_id', $request->district_id);
            });
        }

        // Date Range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('token_date', [$request->from, $request->to]);
        } elseif ($request->filled('from')) {
            $query->where('token_date', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->where('token_date', '<=', $request->to);
        }

        return $query;
    }

    /**
     * Get filter dropdown data
     */
    private function getFilterData()
    {
        return [
            'customers' => Customer::activeCustomers()->get(),
            'products' => ProductCatalog::where('status', 'active')->get(),
            'divisions' => GeoLocation::where('type', 'Division')->get(),
            'districts' => GeoLocation::where('type', 'District')->get(),
            'company_info' => CompanyInfo::first()
        ];
    }

    /**
     * Determine report type based on filters
     */
    private function getReportType($request)
    {
        if ($request->filled('product_id')) {
            return 'product';
        } elseif ($request->filled('customer_id')) {
            return 'customer';
        }
        return 'general';
    }

    /**
     * Export report to PDF or Excel
     */
    private function exportReport($reportData, $filterData, $reportType, $exportType)
    {
        $data = array_merge([
            'reportData' => $reportData,
            'reportType' => $reportType
        ], $filterData);

        $filename = 'Service_Report_' . ucfirst($reportType) . '_' . now()->format('Y_m_d_His');

        return (new ExportService())->exportData(
            $data,
            'Services::reports.export.',
            $filename,
            $exportType
        );
    }

    /**
     * Update solution description (for customer-wise reports)
     */
    public function solutionVerificationStore(Request $request, $id)
{
    $servicePendingToken = ServicePendingToken::findOrFail($id);
    
    $validate = $request->validate([
        'status' => 'required|in:Verified,Unchanged,pending',
        'description' => 'nullable|string|min:10',
    ], [
        'description.min' => 'The description must be at least 10 characters.',
    ]);

    // Update the pending token
    $updateData = [
        'status' => $validate['status'],
    ];

    // Only update description if provided
    if (isset($validate['description']) && !empty($validate['description'])) {
        $updateData['description'] = $validate['description'];
    }

    $servicePendingToken->update($updateData);

    return response()->json([
        'success' => true,
        'message' => 'Solution updated successfully.',
        'data' => $servicePendingToken
    ]);
}

    /**
     * Calculate service fees
     */
    private function calculateServiceFees($serviceMyTask)
    {
        if (!$serviceMyTask) {
            return [
                'service_fee' => 0,
                'spare_parts_fee' => 0,
                'total' => 0
            ];
        }

        $serviceFee = 0;
        $sparePartsFee = 0;

        // Get service bills
        foreach ($serviceMyTask->bills as $bill) {
            dd($bill->product);
            if ($bill->product && stripos($bill->product->tag->name, 'service') !== false) {
                $serviceFee += $bill->amount;
            } else {
                $sparePartsFee += $bill->amount;
            }
        }

        return [
            'service_fee' => $serviceFee,
            'spare_parts_fee' => $sparePartsFee,
            'total' => $serviceFee + $sparePartsFee
        ];
    }
}