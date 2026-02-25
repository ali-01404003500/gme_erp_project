<?php

namespace Modules\SalesTarget\Services;

use Illuminate\Support\Facades\DB;
use Modules\SalesTarget\Models\Target;
use App\Models\User;
use Modules\HRMS\Models\Employee;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\Delivery;
use Modules\HRMS\Models\BillsAndAllowance;
use Modules\Account\Models\Collections\Collection;
use Modules\Account\Models\Transaction;
use Modules\Sales\Models\SalesCommission;
use Modules\HRMS\Models\SalaryGenerate;

class TargetService
{
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
        $endDateTime = new \DateTime($endDate);

        // SalaryGenerate matching এর জন্য Y-m ফরম্যাট
        $startMonthStr = $startDateTime->format('Y-m');
        $endMonthStr = $endDateTime->format('Y-m');

        $startYear = $startDateTime->format('Y');
        $endYear = $endDateTime->format('Y');

        $targetQuery = Target::whereBetween('year', [$startYear, $endYear]);
        if ($selectedUserId) {
            $targetQuery->where('employee_id', $selectedUserId);
        }

        $activeTargets = $targetQuery->get();
        $targetEmployeeIds = $activeTargets->pluck('employee_id')->unique()->toArray();

        $employees = Employee::with(['user.roles', 'employementDetail.designation'])
            ->whereIn('id', $targetEmployeeIds)
            ->get();

        $results = [];

        foreach ($employees as $employee) {
            $user = $employee->user;
            if (!$user) continue;

            //  Target calculation 
            $totalRangeTarget = 0;
            $tempDate = clone $startDateTime;
            while ($tempDate <= $endDateTime) {
                $monthCol = strtolower($tempDate->format('M')) . '_target';
                $currentYear = $tempDate->format('Y');
                $targetRow = $activeTargets->where('employee_id', $employee->id)
                    ->where('year', $currentYear)
                    ->first();
                if ($targetRow) {
                    $totalRangeTarget += (float)$targetRow->$monthCol;
                }
                $tempDate->modify('first day of next month');
                if ($tempDate->format('Y-m') > $endDateTime->format('Y-m')) break;
            }

            //  Fetch Sales Orders 
            $salesOrders = SalesOrder::where('created_by', $user->id)
                ->with(['salesOrderDetails' => function ($q) use ($targetTagName) {
                    $q->whereHas('product.tag', function ($q) use ($targetTagName) {
                        $q->where('name', $targetTagName);
                    });
                }])
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->get();
            $salesDetails = $salesOrders->pluck('salesOrderDetails')->flatten();
            // dd($salesDetails->toArray());
            // dd($salesDetails->sum('amount'), $salesDetails->sum('total_discount'));


            $salesOrderIds = $salesOrders->pluck('id')->toArray();
            $achieved = (float)($salesDetails->sum('amount') - $salesDetails->sum('total_discount'));


            // Costing Logic (Account 5300 - 
            $totalCosting = 0;
            if (!empty($salesOrderIds)) {
                $totalCosting = Transaction::where('transactionable_type', Delivery::class)
                    ->whereHasMorph('transactionable', [Delivery::class], function ($query) use ($salesOrderIds) {
                        $query->whereIn('source_id', $salesOrderIds);
                    })
                    ->where('balance_type', 'debit')
                    ->whereHas('account', function ($query) {
                        $query->where('account_number', 5300);
                    })
                    ->sum('debit_amount');
            }


            // Collection Logic 
            $paidOrders = SalesOrder::where('created_by', $user->id)
                ->where('paid_status', 'paid')
                ->with(['salesOrderDetails' => function ($q) use ($targetTagName) {
                    $q->whereHas('product.tag', function ($q) use ($targetTagName) {
                        $q->where('name', $targetTagName);
                    });
                }])
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->get();

            $paidSalesDetails = $paidOrders->pluck('salesOrderDetails')->flatten();
            $totalCollection = (float)($paidSalesDetails->sum('amount') - $paidSalesDetails->sum('total_discount'));


            // 5. TA & DA calculation
            $bills = BillsAndAllowance::where('employee_id', $employee->id)
                ->whereBetween('date_of_bill_claim', [$startDate, $endDate])
                ->whereIn('status', ['approved', 'paid'])
                ->with(['transportExpenses', 'generalExpenses'])
                ->get();

            $totalTA = 0;
            $totalDA = 0;
            foreach ($bills as $bill) {
                $totalTA += $bill->transportExpenses->sum('final_approved_amount') ?: $bill->transportExpenses->sum('amount');
                $totalDA += $bill->generalExpenses->sum('final_approved_amount') ?: $bill->generalExpenses->sum('amount');
            }

            // 6. Commission calculation 
            $totalCommission = 0;
            if (!empty($salesOrderIds)) {
                $totalCommission = SalesCommission::whereIn('sales_order_id', $salesOrderIds)
                    ->whereIn('status', ['verify', 'approved', 'paid'])
                    ->sum('amount');
            }

            // 7. Salary Expense
            $salaryExpense = (float) \Modules\HRMS\Models\EmployeeSalary::where('employee_id', $employee->id)
                ->where('status', 1)
                ->latest('effective_date')
                ->value('gross') ?? 0;

            $totalOperationalExpense = $totalTA + $totalDA + $totalCommission;

            $results[] = [
                'name' => $employee->full_name,
                'designation' => $employee->employementDetail->designation->name ?? 'N/A',
                'target' => $totalRangeTarget,
                'achieved' => $achieved,
                'costing' => (float)$totalCosting,
                'collection' => (float)$totalCollection,
                'due' => (float)($achieved - $totalCollection),
                'percent' => $totalRangeTarget > 0 ? ($achieved / $totalRangeTarget) * 100 : 0,
                'salary_expense' => $salaryExpense,
                'ta_expense' => (float)$totalTA,
                'da_expense' => (float)$totalDA,
                'commission' => (float)$totalCommission,
                'entertainment' => 0,
                'total_excl_salary' => $totalOperationalExpense,
                'total_incl_salary' => $salaryExpense + $totalOperationalExpense,
                'deals' => $salesOrders->count()
            ];
        }

        return $results;
    }

    public function getAllTargets()
    {
        return Target::with('employee')->orderBy('year', 'desc')->get();
    }

    public function storeMultipleTargets(array $targetsData)
    {
        return DB::transaction(function () use ($targetsData) {
            foreach ($targetsData as $data) {
                if (empty($data['employee_id'])) continue;

                Target::updateOrCreate(
                    [
                        'employee_id' => $data['employee_id'],
                        'year'         => $data['year'] ?? date('Y'),
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

    public function deleteTarget($id)
    {
        return Target::findOrFail($id)->delete();
    }
}
