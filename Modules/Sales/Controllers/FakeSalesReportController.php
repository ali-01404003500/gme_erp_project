<?php

namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use App\Models\User;
use Modules\Inventory\Services\ExportService;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\FakeInvoice;
use Modules\CRM\Models\Customer\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FakeSalesReportController extends Controller
{
    public function index(Request $request)
    {
        // Build query for fake invoices
        $query = FakeInvoice::with([
            'customer',
            'customer.account',
            'details.product',
            'createdBy',
            'createdBy.branch',
            'salesOrder'
        ]);

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get filtered data
        $fakeInvoices = $query->orderBy('created_at', 'desc')->get();

        // Prepare report data
        $reportData = collect();
        foreach ($fakeInvoices as $invoice) {
            // Apply product filter if needed
            if ($request->filled('product_id')) {
                $hasProduct = $invoice->details->contains('product_id', $request->product_id);
                if (!$hasProduct) continue;
            }

            $reportData->push([
                'data' => $invoice,
                'date' => $invoice->invoice_date,
            ]);
        }

        // Sort by date descending
        $reportData = $reportData->sortByDesc('date')->values();

        // Handle export (before pagination)
        if ($request->filled('export_type')) {
            // Get selected columns from request
            $selectedColumns = $request->filled('columns') 
                ? explode(',', $request->columns) 
                : ['invoice-id', 'invoice-datetime', 'branch', 'customer', 'status', 
                   'remarks', 'username', 'reference', 'creation', 'type'];

            $data = [
                'reportData' => $reportData,
                'customers' => Customer::activeCustomers()->get(),
                'branches' => Branch::all(),
                'products' => ProductCatalog::where('status', 'active')->get(),
                'users' => User::whereDoesntHave('roles', function ($query) {
                    $query->where('slug', 'customer');
                })->get(),
                'company_info' => CompanyInfo::first(),
                'selectedColumns' => $selectedColumns,
            ];
            
            $filename = 'Fake_Sales_Report_' . now()->format('Y_m_d_His');
            
            return (new ExportService())->exportData(
                $data,
                'Sales::reports.fake-sales-report.export.',
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
            'customers' => Customer::activeCustomers()->get(),
            'branches' => Branch::all(),
            'products' => ProductCatalog::where('status', 'active')->get(),
            'users' => User::whereDoesntHave('roles', function ($query) {
                $query->where('slug', 'customer');
            })->get(),
            'fakeInvoices' => FakeInvoice::orderBy('created_at', 'desc')->limit(100)->get(),
            'company_info' => CompanyInfo::first(),
        ];

        return view('Sales::reports.fake-sales-report.index', $data);
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, $request)
    {
        // Customer filter
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Branch filter
        if ($request->filled('branch_id')) {
            $query->whereHas('createdBy', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        // Invoice ID filter
        if ($request->filled('invoice_id')) {
            $query->where('invoice_number', 'LIKE', '%' . $request->invoice_id . '%');
        }

        // User filter (Username/Created By)
        if ($request->filled('user_id')) {
            $query->where('created_by', $request->user_id);
        }

        // Status filter
        if ($request->filled('status')) {

            if ($request->status == 'delivered') {
                $query->whereHas('salesOrder', function ($q) {
                    $q->where('status', 'delivered');
                });
            }

            if ($request->status == 'undelivered') {
                $query->whereHas('salesOrder', function ($q) {
                    $q->where('status', 'approved');
                });
            }
        }

        // Date range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('invoice_date', [$request->from, $request->to]);
        } elseif ($request->filled('from')) {
            $query->where('invoice_date', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->where('invoice_date', '<=', $request->to);
        }

        // Product filter
        if ($request->filled('product_id')) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        return $query;
    }

    /**
     * Get invoice status label
     */
    private function getInvoiceStatus($invoice)
    {
        return match($invoice->status) {
            'delivered' => 'Delivered',
            'undelivered' => 'Undelivered',
            'pending' => 'Pending',
            'cancelled' => 'Cancelled',
            default => ucfirst($invoice->status ?? 'N/A')
        };
    }
}