<?php
namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Services\ExportService;
use Modules\Sales\Models\Courier;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\ShipmentVerify;

class ShipmentExplorerReportController extends Controller
{
                                        // -------------------------------------------------------------------------
                                        // CACHE TTL CONSTANTS
                                        // -------------------------------------------------------------------------
    private const REPORT_TTL   = 60;    // 1 min - report data
    private const DROPDOWN_TTL = 3_600; // 1 hr - dropdown data
    private const CUSTOMER_TTL = 3_600; // 1 hr - customer list
    private const COURIER_TTL  = 3_600; // 1 hr - courier list
    private const USER_TTL     = 3_600; // 1 hr - user list
    private const COMPANY_TTL  = 3_600; // 1 hr - company info

    // -------------------------------------------------------------------------
    // PAGINATION
    // -------------------------------------------------------------------------
    private const PER_PAGE = 50;

    public function index(Request $request)
    {
        // Generate cache key based on filters
        $cacheKey = $this->getReportCacheKey($request);

        // Get cached report data
        $reportData = Cache::remember($cacheKey, self::REPORT_TTL, function () use ($request) {
            return $this->buildReportData($request);
        });

        // Handle export (before pagination)
        if ($request->filled('export_type')) {
            // Get selected columns from request or use all by default
            $selectedColumns = $request->filled('columns')
                ? explode(',', $request->columns)
                : ['invoice-id', 'datetime', 'customer', 'courier', 'status', 'shipment-type',
                'amount', 'additional', 'conditional', 'remarks', 'carton', 'receipt-date',
                'receipt-no', 'service-charge', 'service-type', 'delivery-charge', 'delivery-type',
                'other-charge', 'other-type', 'attachment', 'update-by', 'collection-by',
                'approved-by', 'user', 'complete-date', 'challan'];

            $data = [
                'reportData'      => collect($reportData),
                'customers'       => $this->getCustomersForDropdown(),
                'couriers'        => $this->getCouriersForDropdown(),
                'salesOrders'     => $this->getSalesOrdersForDropdown(),
                'users'           => $this->getUsersForDropdown(),
                'company_info'    => $this->getCompanyInfo(),
                'selectedColumns' => $selectedColumns,
            ];

            $filename = 'Shipment_Explorer_Report_' . now()->format('Y_m_d_His');

            return (new ExportService())->exportData(
                $data,
                'Sales::reports.shipment-explorer.export.',
                $filename,
                $request->export_type
            );
        }

        // Paginate the report data
        $reportCollection = collect($reportData);
        $currentPage      = request()->input('page', 1);

        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $reportCollection->forPage($currentPage, self::PER_PAGE),
            $reportCollection->count(),
            self::PER_PAGE,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Prepare view data with cached dropdowns
        $data = [
            'reportData'   => $paginatedData,
            'customers'    => $this->getCustomersForDropdown(),
            'couriers'     => $this->getCouriersForDropdown(),
            'salesOrders'  => $this->getSalesOrdersForDropdown(),
            'users'        => $this->getUsersForDropdown(),
            'company_info' => $this->getCompanyInfo(),
        ];

        return view('Sales::reports.shipment-explorer.index', $data);
    }

    /**
     * Generate unique cache key based on request filters
     */
    private function getReportCacheKey(Request $request): string
    {
        $filters = [
            'shipment_type'    => $request->shipment_type,
            'courier_id'       => $request->courier_id,
            'customer_id'      => $request->customer_id,
            'invoice_id'       => $request->invoice_id,
            'user_id'          => $request->user_id,
            'date_filter_type' => $request->date_filter_type,
            'from'             => $request->from,
            'to'               => $request->to,
        ];

        return 'shipment_explorer_report_' . md5(serialize($filters));
    }

    /**
     * Build report data with optimized queries
     */
    private function buildReportData(Request $request): array
    {
        // Build the query with optimized eager loading
        $query = ShipmentVerify::with([
            'customer',
            'courier',
            'source.source.salesOrderDetails.product',
            'source.source.delivery',
            'source.source.shipment',
            'createdBy',
            'updatedBy',
        ]);

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Order by created_at descending
        $query->orderBy('created_at', 'desc');

        // Get the data
        $shipmentData = $query->get();

        // Transform data for report
        return $this->transformShipmentData($shipmentData);
    }

    /**
     * Get verification details for a specific shipment
     */
    public function getVerificationDetails($shipmentVerifyId)
    {
        try {
            $shipmentVerify = ShipmentVerify::with([
                'customer',
                'courier',
                'source.source.salesOrderDetails.product',
                'source.source.delivery',
                'source.source.shipment',
                'createdBy',
                'updatedBy',
            ])->findOrFail($shipmentVerifyId);

            $salesOrder = $shipmentVerify->source?->source;

            // Calculate amounts
            $invoiceAmount     = $salesOrder->net_amount ?? 0;
            $additionalAmount  = $salesOrder->shipment?->additional_amount ?? 0;
            $dueAmount         = $salesOrder->due_amount ?? 0;
            $conditionalAmount = $dueAmount + $additionalAmount;

            $verificationData = [
                'shipment_verify'    => $shipmentVerify,
                'sales_order'        => $salesOrder,
                'invoice_amount'     => $invoiceAmount,
                'additional_amount'  => $additionalAmount,
                'conditional_amount' => $conditionalAmount,
                'status'             => $this->determineShipmentStatus($shipmentVerify, $salesOrder),
                'shipment_type'      => $salesOrder->shipment?->condition ? 'Condition' : 'Without Condition',
            ];

            return response()->json([
                'success' => true,
                'data'    => $verificationData,
            ]);

        } catch (\Exception $e) {
            Log::error("Error fetching verification details: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch verification details.',
            ], 500);
        }
    }

    /**
     * Update verification status (Accept/Deny)
     */
    public function updateVerificationStatus(Request $request, $shipmentVerifyId)
    {
        $request->validate([
            'action' => 'required|in:accept,deny',
            'remark' => 'nullable|string|max:500',
        ]);

        try {
            $shipmentVerify = ShipmentVerify::findOrFail($shipmentVerifyId);

            if ($request->action === 'accept') {
                $shipmentVerify->update([
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                    'status'      => 'verified',
                ]);

                $message = 'Verification accepted successfully.';
            } else {
                $shipmentVerify->update([
                    'status'        => 'denied',
                    'denial_remark' => $request->remark,
                    'denied_by'     => auth()->id(),
                    'denied_at'     => now(),
                ]);

                $message = 'Verification denied successfully.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);

        } catch (\Exception $e) {
            Log::error("Error updating verification status: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update verification status.',
            ], 500);
        }
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, $request)
    {
        // Shipment Type Filter
        if ($request->filled('shipment_type')) {
            switch ($request->shipment_type) {
                case 'condition':
                    $query->whereHas('source', function ($q) {
                        $q->whereHas('source', function ($q) {
                            $q->whereHas('shipment', function ($q) {
                                $q->where('condition', 1);
                            });
                        });
                    });
                    break;
                case 'without_condition':
                    $query->whereHas('source', function ($q) {
                        $q->whereHas('source', function ($q) {
                            $q->whereHas('shipment', function ($q) {
                                $q->where('condition', 0);
                            });
                        });
                    });
                    break;
            }
        }

        // Courier Filter
        if ($request->filled('courier_id')) {
            $query->where('courier_id', $request->courier_id);
        }

        // Customer Filter
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Invoice ID Filter
        if ($request->filled('invoice_id')) {
            $query->whereHas('source', function ($q) use ($request) {
                $q->whereHas('source', function ($q) use ($request) {
                    $q->where('id', $request->invoice_id);
                });
            });
        }

        // User Filter
        if ($request->filled('user_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('created_by', $request->user_id)
                    ->orWhere('updated_by', $request->user_id);
            });
        }

        // Date Filter
        if ($request->filled('date_filter_type') && $request->filled('from') && $request->filled('to')) {
            switch ($request->date_filter_type) {
                case 'inv_date':
                    $query->whereHas('source.source', function ($q) use ($request) {
                        $q->whereBetween('invoice_date', [$request->from, $request->to]);
                    });
                    break;
                case 'update_date':
                    $query->whereBetween(
                        DB::raw('DATE(updated_at)'),
                        [date('Y-m-d', strtotime($request->from)), date('Y-m-d', strtotime($request->to))]
                    );
                    break;
                case 'complete_date':
                    $query->whereNotNull('approved_at')
                        ->whereBetween('approved_at', [$request->from, $request->to]);
                    break;
            }
        }

        return $query;
    }

    /**
     * Transform shipment data for report display
     */
    private function transformShipmentData($shipmentData)
    {
        $reportData = [];

        foreach ($shipmentData as $shipment) {
            $salesOrder = $shipment->source?->source;

            if (! $salesOrder) {
                continue;
            }

            // Calculate conditional amount
            $invoiceAmount     = $salesOrder->net_amount ?? 0;
            $additionalAmount  = $salesOrder->shipment?->additional_amount ?? 0;
            $dueAmount         = $salesOrder->due_amount ?? 0;
            $conditionalAmount = $dueAmount + $additionalAmount;

            // Determine status
            $status = $this->determineShipmentStatus($shipment, $salesOrder);

            $reportData[] = [
                'shipment_verify_id'     => $shipment->id,
                'invoice_id'             => $salesOrder->sales_order_id,
                'invoice_date'           => $salesOrder->invoice_date,
                'invoice_time'           => $salesOrder->created_at->format('h:i A'),
                'customer_name'          => $shipment->customer?->company_name ?? 'N/A',
                'customer_id'            => $shipment->customer_id,
                'courier_name'           => $shipment->courier?->courier_name ?? 'N/A',
                'courier_id'             => $shipment->courier_id,
                'status'                 => $status,
                'shipment_type'          => $salesOrder->shipment?->condition ? 'Condition' : 'Without Condition',
                'invoice_amount'         => $invoiceAmount,
                'additional_cond_amt'    => $additionalAmount,
                'conditional_amount'     => $status == 'Complete' ? null : $conditionalAmount,
                'con_additional_remarks' => $salesOrder->shipment?->condition_remarks ?? '',
                'carton_no'              => $shipment->cartoon_no ?? '',
                'receipt_date'           => $shipment->receive_date ?? '',
                'receipt_no'             => $shipment->receipt_no ?? '',
                'service_charge'         => $shipment->service_charge ?? 0,
                'service_type'           => $shipment->service_type ?? '',
                'delivery_charge'        => $shipment->delivery_charge ?? 0,
                'delivery_type'          => $shipment->delivery_type ?? '',
                'other_charge'           => $shipment->other_charge ?? 0,
                'other_type'             => $shipment->other_type ?? '',
                'attachment'             => $shipment->files ?? [],
                'update_by'              => $shipment->updatedBy?->name ?? 'N/A',
                'collection_by'          => 'N/A',
                'approved_by'            => 'N/A',
                'user'                   => $shipment->createdBy?->name ?? 'N/A',
                'complete_date'          => 'N/A',
                'challan_no'             => $shipment->challan_no,
                'sales_order'            => $salesOrder,
                'shipment'               => $shipment,
            ];
        }

        return $reportData;
    }

    /**
     * Determine shipment status
     */
    private function determineShipmentStatus($shipment, $salesOrder)
    {
        // Complete: Condition amount approved
        if ($shipment->approved_at) {
            return 'Complete';
        }

        // Request: Condition amount collection request raised
        if ($salesOrder->shipment?->condition && $shipment->status == 'verified' && $shipment->updated_at) {
            return 'Request';
        }

        // Updated: Shipment verification updated
        if ($shipment->status == 'verified') {
            return 'Updated';
        }

        // Pending: Invoice created but shipment not verified
        return 'Pending';
    }

    // =========================================================================
    // DROPDOWN DATA METHODS (Cached)
    // =========================================================================

    /**
     * Get customers for dropdown with caching
     */
    private function getCustomersForDropdown()
    {
        return Cache::remember('shipment_explorer_customers', self::CUSTOMER_TTL, function () {
            return Customer::activeCustomers()
                ->select('id', 'company_name', 'address')
                ->orderBy('company_name')
                ->get();
        });
    }

    /**
     * Get couriers for dropdown with caching
     */
    private function getCouriersForDropdown()
    {
        return Cache::remember('shipment_explorer_couriers', self::COURIER_TTL, function () {
            return Courier::select('id', 'courier_name')
                ->orderBy('courier_name')
                ->get();
        });
    }

    /**
     * Get sales orders for dropdown with caching
     */
    private function getSalesOrdersForDropdown()
    {
        return Cache::remember('shipment_explorer_sales_orders', self::DROPDOWN_TTL, function () {
            return SalesOrder::select('id', 'sales_order_id', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(100)
                ->get();
        });
    }

    /**
     * Get users for dropdown with caching
     */
    private function getUsersForDropdown()
    {
        return Cache::remember('shipment_explorer_users', self::USER_TTL, function () {
            return User::whereDoesntHave('roles', function ($query) {
                $query->where('slug', 'customer');
            })->select('id', 'name')->get();
        });
    }

    /**
     * Get company info with caching
     */
    private function getCompanyInfo()
    {
        return Cache::remember('shipment_explorer_company_info', self::COMPANY_TTL, function () {
            return CompanyInfo::first();
        });
    }
}
