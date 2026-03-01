<?php
namespace Modules\SalesTarget\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Transaction;
use Modules\HRMS\Models\BillsAndAllowance;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSalary;
use Modules\SalesTarget\Models\Target;
use Modules\Sales\Models\SalesCommission;
use Modules\Sales\Models\SalesOrder;

class TargetService
{
    /**
     * Get all employees for dropdown
     */
    public function getAllEmployees()
    {
        return Employee::select('id', 'full_name as display_name')
            ->orderBy('full_name', 'asc')
            ->get();
    }

    public function getYearlyPerformanceSummary($startDate, $endDate, $selectedUserId = null)
    {
        $targetTagName = 'Machine';

        $startDateTime = new \DateTime($startDate);
        $endDateTime   = new \DateTime($endDate);

        $formattedStart = $startDateTime->format('d/m/Y');
        $formattedEnd   = $endDateTime->format('d/m/Y');

        $interval      = $startDateTime->diff($endDateTime);
        $monthsInRange = (($interval->y) * 12) + ($interval->m) + 1;

        $startYear = $startDateTime->format('Y');
        $endYear   = $endDateTime->format('Y');

        $targetQuery = Target::whereBetween('year', [$startYear, $endYear]);
        if ($selectedUserId) {
            $targetQuery->where('employee_id', $selectedUserId);
        }

        $activeTargets     = $targetQuery->get();
        $targetEmployeeIds = $activeTargets->pluck('employee_id')->unique()->toArray();

        $employees = Employee::with(['user.roles', 'employementDetail.designation'])
            ->whereIn('id', $targetEmployeeIds)
            ->get();

        $results = [];

        foreach ($employees as $employee) {
            $user = $employee->user;
            if (! $user) {
                continue;
            }

            // ---------> Target Calculation
            $totalRangeTarget = 0;
            $tempDate         = clone $startDateTime;
            while ($tempDate <= $endDateTime) {
                $monthCol    = strtolower($tempDate->format('M')) . '_target';
                $currentYear = $tempDate->format('Y');
                $targetRow   = $activeTargets->where('employee_id', $employee->id)
                    ->where('year', $currentYear)
                    ->first();
                if ($targetRow) {
                    $totalRangeTarget += (float) $targetRow->$monthCol;
                }
                $tempDate->modify('first day of next month');
                if ($tempDate->format('Y-m') > $endDateTime->format('Y-m')) {
                    break;
                }
            }

            // --------> Achieved Sales
            $salesOrders  = SalesOrder::where('user_ref_id', $employee->id)
                ->with(['salesOrderDetails' => function ($q) use ($targetTagName) {
                    $q->whereHas('product.tag', function ($q) use ($targetTagName) {
                        $q->where('name', $targetTagName);
                    });
                }])
                ->whereBetween('created_at', [$startDateTime->format('Y-m-d') . ' 00:00:00', $endDateTime->format('Y-m-d') . ' 23:59:59'])
                ->get();

            $salesDetails  = $salesOrders->pluck('salesOrderDetails')->flatten();
            $salesOrderIds = $salesOrders->pluck('id')->toArray();
            $achieved      = (float) ($salesDetails->sum('amount') - $salesDetails->sum('total_discount'));

            // --------> Product Costing
            $totalcostPerStock = SalesOrder::where('status', 'delivered')
                ->whereIn('id', $salesOrderIds)
                ->with(['delivery' => function ($q) use ($targetTagName) {
                    $q->with(['deliveryDetails' => function ($q) use ($targetTagName) {
                        $q->whereHas('product.tag', function ($q) use ($targetTagName) {
                            $q->where('name', $targetTagName);
                        });
                    }]);
                }])->get()->pluck('delivery.deliveryDetails')->flatten()->pluck('deliveryStocks')->flatten();

            $totalCosting = 0;
            if ($totalcostPerStock->isNotEmpty()) {
                foreach ($totalcostPerStock as $cost) {
                    $totalCosting += $cost->productCatalog->getLandedPrice($cost->serial_no ?? $cost->lot_no);
                }
            }

            // --------> Collection
            $paidOrders = SalesOrder::where('user_ref_id', $employee->id)
                ->where('paid_status', 'paid')
                ->with(['salesOrderDetails' => function ($q) use ($targetTagName) {
                    $q->whereHas('product.tag', function ($q) use ($targetTagName) {
                        $q->where('name', $targetTagName);
                    });
                }])
                ->whereBetween('created_at', [$startDateTime->format('Y-m-d') . ' 00:00:00', $endDateTime->format('Y-m-d') . ' 23:59:59'])
                ->get();

            $paidSalesDetails = $paidOrders->pluck('salesOrderDetails')->flatten();
            $totalCollection  = (float) ($paidSalesDetails->sum('amount') - $paidSalesDetails->sum('total_discount'));

            // --------> Salary Expense
            $monthlyGross = (float) EmployeeSalary::where('employee_id', $employee->id)
                ->where('status', 1)
                ->where('effective_date', '<=', $endDateTime->format('Y-m-d'))
                ->latest('effective_date')
                ->value('gross') ?? 0;

            $salaryExpense = $monthlyGross * $monthsInRange;

            // --------> TA & DA calculation
            $bills = BillsAndAllowance::where('employee_id', $employee->id)
                ->where('status', 'team_leader_check')
                ->with(['transportExpenses', 'generalExpenses'])
                ->whereBetween('date_of_bill_claim', [$startDateTime->format('Y-m-d'), $endDateTime->format('Y-m-d')])
                ->get();

            $totalTA = 0;
            $totalDA = 0;

            foreach ($bills as $bill) {
                $totalTA += $bill->transportExpenses->sum(function ($item) {
                    return $item->final_approved_amount ?: ($item->accounts_approved_amount ?: ($item->team_leader_approved_amount ?: $item->amount));
                });

                $totalDA += $bill->generalExpenses->sum(function ($item) {
                    return $item->final_approved_amount ?: ($item->accounts_approved_amount ?: ($item->team_leader_approved_amount ?: $item->amount));
                });
            }

            // --------> Commission
            $totalCommission = 0;
            if (! empty($salesOrderIds)) {
                $totalCommission = SalesCommission::whereIn('sales_order_id', $salesOrderIds)
                    ->whereIn('status', ['verify', 'approved', 'paid'])
                    ->sum('amount');
            }

            $totalOperationalExpense = $totalTA + $totalDA + (float) $totalCommission;

            $results[] = [
                'name'              => $employee->full_name,
                'designation'       => $employee->employementDetail->designation->name ?? 'N/A',
                'period_display'    => $formattedStart . ' - ' . $formattedEnd,
                'target'            => $totalRangeTarget,
                'achieved'          => $achieved,
                'costing'           => (float) $totalCosting,
                'collection'        => (float) $totalCollection,
                'due'               => (float) ($achieved - $totalCollection),
                'percent'           => $totalRangeTarget > 0 ? ($achieved / $totalRangeTarget) * 100 : 0,
                'salary_expense'    => $salaryExpense,
                'ta_expense'        => (float) $totalTA,
                'da_expense'        => (float) $totalDA,
                'commission'        => (float) $totalCommission,
                'entertainment'     => 0,
                'total_excl_salary' => $totalOperationalExpense,
                'total_incl_salary' => $salaryExpense + $totalOperationalExpense,
                'deals'             => $salesOrders->count(),
            ];
        }

        return $results;
    }

    /**
     * Get all targets for setting index
     */
    public function getAllTargets()
    {
        return Target::with('employee')->orderBy('year', 'desc')->get();
    }

    /**
     * Store multiple targets
     */
    public function storeMultipleTargets(array $targetsData)
    {
        return DB::transaction(function () use ($targetsData) {
            foreach ($targetsData as $data) {
                if (empty($data['employee_id'])) {
                    continue;
                }

                Target::updateOrCreate(
                    [
                        'employee_id' => $data['employee_id'],
                        'year'        => $data['year'] ?? date('Y'),
                    ],
                    [
                        'jan_target'   => $data['jan_target'] ?? 0,
                        'feb_target'   => $data['feb_target'] ?? 0,
                        'mar_target'   => $data['mar_target'] ?? 0,
                        'apr_target'   => $data['apr_target'] ?? 0,
                        'may_target'   => $data['may_target'] ?? 0,
                        'jun_target'   => $data['jun_target'] ?? 0,
                        'jul_target'   => $data['jul_target'] ?? 0,
                        'aug_target'   => $data['aug_target'] ?? 0,
                        'sep_target'   => $data['sep_target'] ?? 0,
                        'oct_target'   => $data['oct_target'] ?? 0,
                        'nov_target'   => $data['nov_target'] ?? 0,
                        'dec_target'   => $data['dec_target'] ?? 0,
                        'total_target' => $data['total_target'] ?? 0,
                    ]
                );
            }
        });
    }

    /**
     * Delete target by ID
     */
    public function deleteTarget($id)
    {
        return Target::findOrFail($id)->delete();
    }
}
