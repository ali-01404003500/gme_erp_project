<?php

namespace Modules\HRMS\Services;

use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Approver;
use Modules\HRMS\Models\Employee;

class ApproverService
{
    public function getAvailableApprovers($employeeId, $search = null)
    {
        $existingApproverIds = Approver::where('employee_id', $employeeId)
            ->pluck('approver_id')
            ->toArray();

        $query = Employee::where('id', '!=', $employeeId)
            ->where('status', 1)
            ->whereNotIn('id', $existingApproverIds);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('epf_number', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('full_name', 'ASC')
                     ->with('designation')
                     ->get(['id', 'full_name', 'designation_id', 'epf_number']);
    }

    public function getCurrentApprovers($employeeId)
    {
        return Approver::with('approver.designation')
            ->where('employee_id', $employeeId)
            ->orderBy('hierarchy_level')
            ->get();
    }

    public function addApprovers( array $approvers)
    {
        DB::beginTransaction();
        try {
            foreach ($approvers['approver_ids'] as $key=> $approver_ids) {
                Approver::where('employee_id', $approvers['employee_id'])
                ->whereNotIn('id', $approvers['approver_update_id']??[])
                ->delete();
           
                Approver::updateOrCreate([
                    'id'=> $approvers['approver_update_id'][ $key]??null,
                ],[
                    'hierarchy_level'=>$key + 1,
                    'employee_id' =>  $approvers['employee_id'],
                    'approver_id' =>$approver_ids,
                ]);
            }
            DB::commit();
            return ['success' => true, 'message' => 'Approver added successfully'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function removeApproverById($id)
    {
        $approver = Approver::find($id);
        if ($approver) {
            $empId = $approver->employee_id;
            $approver->delete();
            $this->reorderHierarchy($empId);
        }
    }

    private function reorderHierarchy($employeeId)
    {
        $approvers = Approver::where('employee_id', $employeeId)->orderBy('hierarchy_level')->get();
        foreach ($approvers as $index => $approver) {
            $approver->update(['hierarchy_level' => $index + 1]);
        }
    }
}
