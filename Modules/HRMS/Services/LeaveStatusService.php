<?php
namespace Modules\HRMS\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\LeaveStatus;

class LeaveStatusService
{
    public function storeLeaveStatus(array $data)
    {
        return DB::transaction(function () use ($data) {
            if ($data['type'] === 'employee_wise') {
                return $this->assignSingle($data);
            } else {
                return $this->assignBulk($data);
            }
        });
    }

    private function assignSingle($data)
    {

        LeaveStatus::where('employee_id', $data['employee_id'])
            ->where('is_active', true)
            ->update(['is_active' => false]);

        return LeaveStatus::create([
            'employee_id'    => $data['employee_id'],
            'leave_group_id' => $data['leave_group_id'],
            'join_date'      => $data['join_date'] ?? null,
            'effective_date' => $data['effective_date'],
            'is_active'      => true,
        ]);
    }

    private function assignBulk($data)
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
