<?php

namespace Modules\Services\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Services\Models\ServiceToken;
use Modules\Services\Models\ServiceMyTask;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Settings\Department;
use Modules\Inventory\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthlyServiceReportController extends Controller
{
    /**
     * Display Monthly Service Report
     */
    public function index(Request $request)
    {
        // Get filter data
        $filterData = $this->getFilterData();

        // Otherwise show summary view
        return $this->showSummaryView($request, $filterData);
    }

    /**
     * Get Engineer Details via AJAX
     */
    public function getEngineerDetails(Request $request)
    {
        $engineerId = $request->engineer_id;
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ?? now()->endOfMonth()->format('Y-m-d');

        // Get engineer service details
        $serviceDetails = $this->getEngineerServiceDetails($engineerId, $from, $to);

        // Format data for response
        $formattedDetails = [];
        $totalServiceSales = 0;
        $totalSpareSales = 0;

        foreach ($serviceDetails as $detail) {
            $formattedDetails[] = [
                'date' => $detail['date'] ? \Carbon\Carbon::parse($detail['date'])->format('d-M-Y') : 'N/A',
                'customer_name' => $detail['customer_name'],
                'token_id' => $detail['token']->service->service_unique_id ?? 'N/A',
                'service_sales' => number_format($detail['service_sales']),
                'spare_sales' => number_format($detail['spare_sales']),
                'invoice_amount' => number_format($detail['invoice_amount']),
                'service_id' => $detail['service_id']
            ];

            $totalServiceSales += $detail['service_sales'];
            $totalSpareSales += $detail['spare_sales'];
        }

        return response()->json([
            'success' => true,
            'data' => $formattedDetails,
            'totals' => [
                'service_sales' => number_format($totalServiceSales),
                'spare_sales' => number_format($totalSpareSales),
                'grand_total' => number_format($totalServiceSales + $totalSpareSales)
            ]
        ]);
    }

    /**
     * Show Summary View (Engineer-wise)
     */
    private function showSummaryView($request, $filterData)
    {
        // Get date range (default to current month)
        $from = $request->filled('from') ? $request->from : now()->startOfMonth()->format('Y-m-d');
        $to = $request->filled('to') ? $request->to : now()->endOfMonth()->format('Y-m-d');

        // Get all service engineers
        $department_id = Department::withoutGlobalScope('latest')
            ->whereIn('name', ['Sales & Service', 'Sales & Marketing'])
            ->pluck('id')
            ->toArray();

        $engineers = Employee::withoutGlobalScope('latest')
            ->whereHas('employementDetail', function($q) use ($department_id) {
                $q->withoutGlobalScope('latest')
                  ->whereIn('department_id', $department_id);
            })
            ->get();

        // Calculate sales for each engineer
        $engineerReports = [];
        $totalServiceSales = 0;
        $totalSpareSales = 0;

        foreach ($engineers as $engineer) {
            $sales = $this->calculateEngineerSales($engineer->id, $from, $to);
            
            if ($sales['service_sales'] > 0 || $sales['spare_sales'] > 0) {
                $engineerReports[] = [
                    'engineer' => $engineer,
                    'service_sales' => $sales['service_sales'],
                    'spare_sales' => $sales['spare_sales'],
                    'total_amount' => $sales['total_amount']
                ];

                $totalServiceSales += $sales['service_sales'];
                $totalSpareSales += $sales['spare_sales'];
            }
        }

        $totals = [
            'total_service_sales' => $totalServiceSales,
            'total_spare_sales' => $totalSpareSales,
            'grand_total' => $totalServiceSales + $totalSpareSales,
            'total_engineers' => count($engineerReports)
        ];

        // Handle exports
        if ($request->filled('export_type')) {
            return $this->exportReport($engineerReports, $filterData, $totals, $request, $from, $to);
        }

        return view('Services::monthly-service-report.index', [
            'engineerReports' => $engineerReports,
            'totals' => $totals,
            'engineers' => $filterData['engineers'],
            'company_info' => $filterData['company_info'],
            'from' => $from,
            'to' => $to
        ]);
    }

    /**
     * Calculate sales for a specific engineer
     */
    private function calculateEngineerSales($engineerId, $from, $to)
    {
        $serviceSales = 0;
        $spareSales = 0;

        // Get all service tokens assigned to this engineer within date range
        $serviceTokens = ServiceToken::withoutGlobalScope('latest')
            ->whereHas('engineerAssign.engineers', function($q) use ($engineerId) {
                $q->withoutGlobalScope('latest')
                  ->where('engineer_id', $engineerId);
            })
            ->whereBetween('token_date', [$from, $to])
            ->with([
                'serviceMyTask' => function($q) {
                    $q->withoutGlobalScope('latest')
                      ->with([
                          'bills' => function($q) {
                              $q->withoutGlobalScope('latest')
                                ->with(['product.tag' => function($q) {
                                    $q->withoutGlobalScope('latest');
                                }]);
                          },
                          'returnBills' => function($q) {
                              $q->withoutGlobalScope('latest')
                                ->with(['product.tag' => function($q) {
                                    $q->withoutGlobalScope('latest');
                                }]);
                          }
                      ]);
                }
            ])
            ->get();

        foreach ($serviceTokens as $token) {
            if (!$token->serviceMyTask) continue;

            // Calculate from service bills
            foreach ($token->serviceMyTask->bills as $bill) {
                $amount = floatval($bill->amount ?? 0);
                
                if ($bill->product && $bill->product->tag && 
                    stripos($bill->product->tag->name, 'service') !== false) {
                    $serviceSales += $amount;
                } else {
                    $spareSales += $amount;
                }
            }

            // Calculate from return bills (subtract)
            foreach ($token->serviceMyTask->returnBills as $returnBill) {
                $amount = floatval($returnBill->amount ?? 0);
                
                if ($returnBill->product && $returnBill->product->tag && 
                    stripos($returnBill->product->tag->name, 'service') !== false) {
                    $serviceSales -= $amount;
                } else {
                    $spareSales -= $amount;
                }
            }
        }

        return [
            'service_sales' => max(0, $serviceSales),
            'spare_sales' => max(0, $spareSales),
            'total_amount' => max(0, $serviceSales + $spareSales)
        ];
    }

    /**
     * Get detailed service records for specific engineer
     */
    private function getEngineerServiceDetails($engineerId, $from, $to)
    {
        $details = [];

        // Get all service tokens assigned to this engineer
        $serviceTokens = ServiceToken::withoutGlobalScope('latest')
            ->whereHas('engineerAssign.engineers', function($q) use ($engineerId) {
                $q->withoutGlobalScope('latest')
                  ->where('engineer_id', $engineerId);
            })
            ->whereBetween('token_date', [$from, $to])
            ->with([
                'service' => function($q) {
                    $q->withoutGlobalScope('latest');
                },
                'customer' => function($q) {
                    $q->withoutGlobalScope('latest');
                },
                'serviceMyTask' => function($q) {
                    $q->withoutGlobalScope('latest')
                      ->with([
                          'bills' => function($q) {
                              $q->withoutGlobalScope('latest')
                                ->with(['product.tag' => function($q) {
                                    $q->withoutGlobalScope('latest');
                                }]);
                          },
                          'returnBills' => function($q) {
                              $q->withoutGlobalScope('latest')
                                ->with(['product.tag' => function($q) {
                                    $q->withoutGlobalScope('latest');
                                }]);
                          }
                      ]);
                }
            ])
            ->orderBy('token_date', 'desc')
            ->get();

        foreach ($serviceTokens as $token) {
            if (!$token->serviceMyTask) continue;

            $serviceSales = 0;
            $spareSales = 0;

            // Calculate from bills
            foreach ($token->serviceMyTask->bills as $bill) {
                $amount = floatval($bill->amount ?? 0);
                
                if ($bill->product && $bill->product->tag && 
                    stripos($bill->product->tag->name, 'service') !== false) {
                    $serviceSales += $amount;
                } else {
                    $spareSales += $amount;
                }
            }

            // Calculate from return bills
            foreach ($token->serviceMyTask->returnBills as $returnBill) {
                $amount = floatval($returnBill->amount ?? 0);
                
                if ($returnBill->product && $returnBill->product->tag && 
                    stripos($returnBill->product->tag->name, 'service') !== false) {
                    $serviceSales -= $amount;
                } else {
                    $spareSales -= $amount;
                }
            }

            $details[] = [
                'token' => $token,
                'date' => $token->token_date,
                'customer_name' => $token->customer->company_name ?? 'N/A',
                'service_sales' => max(0, $serviceSales),
                'spare_sales' => max(0, $spareSales),
                'invoice_amount' => max(0, $serviceSales + $spareSales),
                'service_id' => $token->service_id
            ];
        }

        return $details;
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
            'engineers' => Employee::withoutGlobalScope('latest')
                ->whereHas('employementDetail', function($q) use ($department_id) {
                    $q->withoutGlobalScope('latest')
                      ->whereIn('department_id', $department_id);
                })
                ->get(),
            'company_info' => CompanyInfo::withoutGlobalScope('latest')
                ->first()
        ];
    }

    /**
     * Export Report
     */
    private function exportReport($engineerReports, $filterData, $totals, $request, $from, $to)
    {
        $data = array_merge([
            'engineerReports' => $engineerReports,
            'totals' => $totals,
            'filters' => ['from' => $from, 'to' => $to]
        ], $filterData);

        $filename = 'Monthly_Service_Report_' . now()->format('Y_m_d_His');

        return (new ExportService())->exportData(
            $data,
            'Services::monthly-service-report.export.',
            $filename,
            $request->export_type
        );
    }
}