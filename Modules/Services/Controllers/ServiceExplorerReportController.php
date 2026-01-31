<?php

namespace Modules\Services\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Services\Models\ServiceToken;
use Modules\Services\Models\ServiceMyTask;
use Modules\Services\Models\EngineerAssign;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Settings\Department;
use Modules\Inventory\Services\ExportService;

class ServiceExplorerReportController extends Controller
{
    public function index(Request $request)
    {
        // Base query with all necessary relationships - without global scopes
        $query = ServiceToken::withoutGlobalScope('latest')
            ->with([
                'service' => function($q) {
                    $q->withoutGlobalScope('latest');
                },
                'customer' => function($q) {
                    $q->withoutGlobalScope('latest');
                },
                'product' => function($q) {
                    $q->withoutGlobalScope('latest');
                },
                'serviceMyTask' => function($q) {
                    $q->withoutGlobalScope('latest')
                      ->with([
                          'bills' => function($q) {
                              $q->withoutGlobalScope('latest')
                                ->with([
                                    'product' => function($q) {
                                        $q->withoutGlobalScope('latest')
                                          ->with([
                                              'tag' => function($q) {
                                                  $q->withoutGlobalScope('latest');
                                              }
                                          ]);
                                    }
                                ]);
                          },
                          'returnBills' => function($q) {
                              $q->withoutGlobalScope('latest');
                          },
                          'createdBy' => function($q) {
                              $q->withoutGlobalScope('latest');
                          }
                      ]);
                },
                'engineerAssign' => function($q) {
                    $q->withoutGlobalScope('latest')
                      ->with([
                          'engineers' => function($q) {
                              $q->withoutGlobalScope('latest');
                          }
                      ]);
                },
                'service.createdBy' => function($q) {
                    $q->withoutGlobalScope('latest');
                }
            ]);

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get report data with explicit table reference in ORDER BY
        $reportData = $query->orderBy('service_tokens.token_date', 'desc')
                           ->orderBy('service_tokens.created_at', 'desc')
                           ->get();

        // Calculate totals
        $totals = $this->calculateTotals($reportData);

        // Get filter data for dropdowns
        $filterData = $this->getFilterData();

        // Handle exports
        if ($request->filled('export_type')) {
            return $this->exportReport($reportData, $filterData, $totals, $request);
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

        return view('Services::service-explorer-report.index', [
            'reportData' => $paginatedData,
            'totals' => $totals,
            'customers' => $filterData['customers'],
            'products' => $filterData['products'],
            'engineers' => $filterData['engineers'],
            'serviceTokens' => $filterData['serviceTokens'],
            'company_info' => $filterData['company_info']
        ]);
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, $request)
    {
        // Customer filter
        if ($request->filled('customer_id')) {
            $query->where('service_tokens.customer_id', $request->customer_id);
        }

        // Product filter
        if ($request->filled('product_id')) {
            $query->where('service_tokens.product_id', $request->product_id);
        }

        // Token ID filter
        if ($request->filled('token_id')) {
            $query->where('service_tokens.id', $request->token_id);
        }

        // Serial ID filter
        if ($request->filled('serial_no')) {
            $query->where('service_tokens.serial_number', 'LIKE', '%' . $request->serial_no . '%');
        }

        // Engineer filter
        if ($request->filled('engineer_id')) {
            $query->whereHas('engineerAssign.engineers', function($q) use ($request) {
                $q->withoutGlobalScope('latest')
                  ->where('engineer_id', $request->engineer_id);
            });
        }

        // Date Range filter (REQUIRED)
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('service_tokens.token_date', [$request->from, $request->to]);
        } elseif ($request->filled('from')) {
            $query->where('service_tokens.token_date', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->where('service_tokens.token_date', '<=', $request->to);
        }

        // Service Status filter
        if ($request->filled('status')) {
            $status = $request->status;
            
            switch ($status) {
                case 'Live':
                case 'Started':
                case 'Done':
                case 'Cancelled':
                    $query->whereHas('serviceMyTask', function($q) use ($status) {
                        $q->withoutGlobalScope('latest')
                          ->where(function($subQ) use ($status) {
                              $subQ->where('status', strtolower($status))
                                   ->orWhere('action', $status);
                          });
                    });
                    break;
            }
        }

        // Service Type filter
        if ($request->filled('service_type') && $request->service_type !== 'All') {
            $query->where('service_tokens.service_type', $request->service_type);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('service_tokens.token_no', 'LIKE', "%{$search}%")
                  ->orWhere('service_tokens.serial_no', 'LIKE', "%{$search}%")
                  ->orWhere('service_tokens.problem_details', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->withoutGlobalScope('latest')
                        ->where('company_name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('product', function($q) use ($search) {
                      $q->withoutGlobalScope('latest')
                        ->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        return $query;
    }

    /**
     * Get filter dropdown data
     */
    private function getFilterData()
    {
        $department_id = Department::withoutGlobalScope('latest')
            ->whereIn('name', ['Sales & Service', 'Sales & Marketing'])
            ->pluck('id')
            ->toArray();
      
        return [
            'customers' => Customer::withoutGlobalScope('latest')
                ->activeCustomers()
                ->get(),
            'products' => ProductCatalog::withoutGlobalScope('latest')
                ->where('status', 'active')
                ->get(),
            'engineers' => Employee::withoutGlobalScope('latest')
                ->whereHas('employementDetail', function($q) use ($department_id) {
                    $q->withoutGlobalScope('latest')
                      ->whereIn('department_id', $department_id);
                })
                ->get(),
            'serviceTokens' => ServiceToken::withoutGlobalScope('latest')
                ->select('id', 'service_id')
                ->with(['service' => function($q) {
                    $q->withoutGlobalScope('latest')
                      ->select('id', 'service_unique_id');
                }])
                ->latest('service_tokens.created_at')
                ->limit(500)
                ->get(),
            'company_info' => CompanyInfo::withoutGlobalScope('latest')
                ->first()
        ];
    }

    /**
     * Calculate totals for the report
     */
    private function calculateTotals($reportData)
    {
        $totalServiceBill = 0;
        $totalProductBill = 0;

        foreach ($reportData as $token) {
            if ($token->serviceMyTask) {
                foreach ($token->serviceMyTask->bills as $bill) {
                    $amount = floatval($bill->amount ?? 0);
                    
                    // Check if it's a service bill or product bill
                    if ($bill->product && 
                        $bill->product->tag && 
                        stripos($bill->product->tag->name, 'service') !== false) {
                        $totalServiceBill += $amount;
                    } else {
                        $totalProductBill += $amount;
                    }
                }
            }
        }

        return [
            'total_service_bill' => $totalServiceBill,
            'total_product_bill' => $totalProductBill,
            'grand_total' => $totalServiceBill + $totalProductBill,
            'total_records' => $reportData->count()
        ];
    }

    /**
     * Export report to PDF or Excel
     */
    private function exportReport($reportData, $filterData, $totals, $request)
    {
        $data = array_merge([
            'reportData' => $reportData,
            'totals' => $totals,
            'filters' => $request->all()
        ], $filterData);

        $filename = 'Service_Explorer_Report_' . now()->format('Y_m_d_His');

        return (new ExportService())->exportData(
            $data,
            'Services::service-explorer-report.export.',
            $filename,
            $request->export_type
        );
    }
}