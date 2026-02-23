<?php

namespace Modules\SalesTarget\Services;

use Illuminate\Support\Facades\DB;
use Modules\SalesTarget\Models\Target;
use App\Models\User;
use Modules\HRMS\Models\Employee;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\Delivery;
use Modules\HRMS\Models\BillsAndAllowance;

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
        $machineTags = ['IC', 'BC', 'CC', 'Machine', 'I-Chroma Machine'];

        $startDateTime = new \DateTime($startDate);
        $endDateTime = new \DateTime($endDate);
        $startYear = $startDateTime->format('Y');
        $endYear = $endDateTime->format('Y');


        $targetQuery = Target::whereBetween('year', [$startYear, $endYear]);
        if ($selectedUserId) {
            $targetQuery->where('employee_id', $selectedUserId);
        }

        $activeTargets = $targetQuery->get();
        $targetEmployeeIds = $activeTargets->pluck('employee_id')->unique()->toArray();


        // get employees
        $employees = Employee::with(['user.roles', 'employementDetail.designation'])
            ->whereIn('id', $targetEmployeeIds)
            ->get();

        $results = [];

        foreach ($employees as $employee) {
            $user = $employee->user;
            if (!$user) continue;


            // target data and total range target
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


            // users sales order data 
            $salesOrders = SalesOrder::where('created_by', $user->id)
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->whereHas('salesOrderDetails.product.tag', function ($q) use ($machineTags) {
                    $q->whereIn('name', $machineTags);
                })->get();

            $salesOrderIds = $salesOrders->pluck('id')->toArray();


            // costing logic included 
            $totalCosting = 0;
            if (!empty($salesOrderIds)) {
                $totalCosting = DB::table('transactions')
                    ->join('deliveries', function ($join) {
                        $join->on('transactions.transactionable_id', '=', 'deliveries.id')
                            ->where('transactions.transactionable_type', '=', 'Modules\Sales\Models\Delivery');
                    })
                    ->whereIn('deliveries.source_id', $salesOrderIds)
                    ->where('transactions.account_id', function ($query) {
                        $query->select('id')->from('accounts')->where('account_number', 5300)->limit(1);
                    })
                    ->where('transactions.balance_type', 'debit')
                    ->sum('transactions.debit_amount');
            }


            // collection logic included
            $totalCollection = 0;
            if (!empty($salesOrderIds)) {
                $totalCollection = DB::table('collections')
                    ->whereIn('source_id', $salesOrderIds)
                    ->where('source_type', SalesOrder::class)
                    ->where('status', 'approved')
                    ->sum('total_amount');
            }


            // TA & DA calculation included
            $bills = BillsAndAllowance::where('employee_id', $employee->id)
                ->whereBetween('date_of_bill_claim', [$startDate, $endDate])
                ->whereIn('status', ['approved', 'paid'])
                ->with(['transportExpenses', 'generalExpenses'])
                ->get();

            $totalTA = 0;
            $totalDA = 0;

            foreach ($bills as $bill) {
                // TA = Transport Expenses (Final Approved Amount)
                $totalTA += $bill->transportExpenses->sum(function ($exp) {
                    return $exp->final_approved_amount ?? 0;
                });

                // DA = General Expenses (Final Approved Amount)
                $totalDA += $bill->generalExpenses->sum(function ($exp) {
                    return $exp->final_approved_amount ?? 0;
                });
            }

            $achieved = (float)$salesOrders->sum('net_amount');
            $salaryExpense = (float)($employee->salary ?? 0);


            //  (TA + DA)
            $totalOperationalExpense = $totalTA + $totalDA;

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
                'commission' => 0,
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
        return DB::transaction(function ($targetsData) {
            foreach ($targetsData as $data) {
                if (empty($data['employee_id'])) continue;

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


    public function deleteTarget($id)
    {
        return Target::findOrFail($id)->delete();
    }
}
