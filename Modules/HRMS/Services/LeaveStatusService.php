<?php
namespace Modules\HRMS\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\LeaveStatus;

class LeaveStatusService
{
    public function storeLeaveStatus(array $data, array $leaveBalanceDetails)
    {  
        return DB::transaction(function () use ($data,$leaveBalanceDetails) {
            if ($data['type'] === 'employee_wise') {
                return $this->assignSingle($data, $leaveBalanceDetails);
            } else {
                return $this->assignBulk($data, $leaveBalanceDetails);
            }
        });
    }

    private function assignSingle($data, $leaveBalanceDetails)
    {
       

        LeaveStatus::where('employee_id', $data['employee_id'])
            ->where('is_active', true)
            ->update(['is_active' => false]);


        // Store leave balance
        foreach ($leaveBalanceDetails['leave_type'] as $key => $leaveType) {
            $result[] = LeaveStatus::create([
                'employee_id'    => $data['employee_id'],
                'leave_group_id' => $data['leave_group_id'],
                'leave_year_id' => $data['leave_year_id'], 
                'join_date'      => $data['join_date'] ?? null,
                'effective_date' => $data['effective_date'],
                'leave_type' => $leaveBalanceDetails['leave_type'][$key], 
                'groupwise_balance' => $leaveBalanceDetails['groupwise_balance'][$key], 
                'remaining_balance' => $leaveBalanceDetails['remaining_balance'][$key],
                'balance_forwarded' => $leaveBalanceDetails['balance_forwarded'][$key], 
                'max_forward_balance' => $leaveBalanceDetails['max_forward_balance'][$key], 
                'continuous' => $leaveBalanceDetails['continuous'][$key], 
                'continuous_sanction' => $leaveBalanceDetails['continuous_sanction'][$key], 
                'half_day' => $leaveBalanceDetails['half_day'][$key], 
                'max_sanction_per_year' => $leaveBalanceDetails['max_sanction_per_year'][$key],  
                'is_active'      => true,
            ]);
        } 
        return $result;
 

    }

    private function assignBulk($data,$leaveBalanceDetails)
    {
        $query  = Employee::query();
        $jobCol = Schema::hasColumn('employees', 'job_base') ? 'job_base' : 'employment_type';

        if (! empty($data['job_base'])) {
            $query->where($jobCol, $data['job_base']);
        }
        if (! empty($data['branch'])) {
            $branchCol = Schema::hasColumn('employees', 'branch_id') ? 'branch_id' : 'branch';
            $query->where($branchCol, $data['branch']);
        }
        if (! empty($data['gender'])) {
            $query->where('gender', $data['gender']);
        }

        $targetEmployees = $query->whereDoesntHave('leaveStatus', function ($q) {
            $q->where('is_active', true);
        })->get();

        $count = 0;
        foreach ($targetEmployees as $emp) {
            LeaveStatus::create([
                'employee_id'    => $emp->id,
                'leave_group_id' => $data['leave_group_id'],
                'join_date'      => $emp->joining_date ?? null,
                'effective_date' => $data['effective_date'],
                'is_active'      => true,
            ]);
            $count++;
        }
        return $count;
    }
}
