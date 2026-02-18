<?php

namespace Modules\SalesTarget\Services;

use Modules\SalesTarget\Models\Target;
use App\Models\User;
use Modules\HRMS\Models\Employee;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Models\SalesOrder;

class TargetService
{
    // public function getAllEmployees()
    // {

    //     $hasTargetIds = Target::distinct()->pluck('employee_id');
    //     return Employee::whereIn('id', $hasTargetIds)
    //         ->select('id', 'full_name as display_name')
    //         ->get();
    // }

    public function getAllEmployees()
    {

        return Employee::select('id', 'full_name as display_name')
            ->orderBy('full_name', 'asc')
            ->get();
    }



    public function getYearlyPerformanceSummary($year, $selectedUserId = null)
    {
        $machineTags = ['IC', 'BC', 'CC', 'Machine', 'I-Chroma Machine'];


        $targetQuery = Target::where('year', $year);
        if ($selectedUserId) {
            $targetQuery->where('employee_id', $selectedUserId);
        }

        $activeTargets = $targetQuery->get();
        $targetEmployeeIds = $activeTargets->pluck('employee_id')->toArray();

        $employees = Employee::with(['user.roles', 'employementDetail.designation'])
            ->whereIn('id', $targetEmployeeIds)
            ->get();

        $results = [];

        foreach ($employees as $employee) {
            $user = $employee->user;
            if (!$user) continue;


            $targetRecord = $activeTargets->where('employee_id', $employee->id)->first();
            $annualTarget = $targetRecord ? (float)$targetRecord->total_target : 0;


            $salesOrders = SalesOrder::where('created_by', $user->id)
                ->whereYear('created_at', $year)
                ->whereHas('salesOrderDetails.product.tag', function ($q) use ($machineTags) {
                    $q->whereIn('name', $machineTags);
                })->get();

            $achieved = (float)$salesOrders->sum('net_amount');
            $salaryExpense = (float)($employee->salary ?? 0);


            $results[] = [
                'name' => $employee->full_name,
                'designation' => $employee->employementDetail->designation->name ?? 'N/A',
                'target' => $annualTarget,
                'achieved' => $achieved,
                'costing' => 0,
                'collection' => (float)$salesOrders->sum('paid_amount'),
                'due' => (float)$salesOrders->sum('due_amount'),
                'percent' => $annualTarget > 0 ? ($achieved / $annualTarget) * 100 : 0,
                'salary_expense' => $salaryExpense,
                'ta_expense' => 0,
                'da_expense' => 0,
                'commission' => 0,
                'entertainment' => 0,
                'total_excl_salary' => 0,
                'total_incl_salary' => $salaryExpense,
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
