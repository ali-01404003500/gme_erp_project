<?php

namespace Modules\HRMS\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\HRMS\Models\Approver;
use Modules\HRMS\Models\Employee;

class ApproverService
{
    
     public function getAvailableApprovers($employeeId, $search = null)
    {
        $query = Employee::where('id', '!=', $employeeId) // Self exclude
            ->where('status', 1)
            ->whereDoesntHave('approverRecords', function($q) use ($employeeId) {
                $q->where('employee_id', $employeeId);
            });

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%")
                  ->orWhere('epf_number', 'LIKE', "%{$search}%");
            });
        }

        return $query->select('id', 'full_name', 'epf_number', 'designation')
            ->orderBy('full_name')
            ->limit(20)
            ->get();
    }

    /**
     * Get current approvers for an employee
     */
    public function getCurrentApprovers($employeeId)
    {
        return Approver::with('approver:id,full_name,epf_number,designation')
            ->where('employee_id', $employeeId)
            ->orderBy('hierarchy_level')
            ->get();
    }

    /**
     * Add approvers to employee
     */
    public function addApprovers($employeeId, array $approverIds)
    {
        try {
        DB::beginTransaction();

        // Check for existing approvers
        $existingApprovers = Approver::where('employee_id', $employeeId)
            ->whereIn('approver_id', $approverIds)
            ->pluck('approver_id')
            ->toArray();

        if (!empty($existingApprovers)) {
            $names = Employee::whereIn('id', $existingApprovers)
                ->pluck('full_name')
                ->join(', ');
                
            throw new \Exception("Already exists: {$names}");
        }

        // Check for self-approval
        if (in_array($employeeId, $approverIds)) {
            throw new \Exception("Employee cannot be their own approver");
        }

        // Get current max hierarchy
        $currentMaxLevel = Approver::where('employee_id', $employeeId)
            ->max('hierarchy_level') ?? 0;

        // Add new approvers
        foreach ($approverIds as $index => $approverId) {
            Approver::create([
                'employee_id' => $employeeId,
                'approver_id' => $approverId,
                'hierarchy_level' => $currentMaxLevel + $index + 1
            ]);
        }

        DB::commit();
        return ['success' => true, 'message' => 'Approvers added successfully'];

    } catch (\Exception $e) {
        DB::rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
    }

    /**
     * Remove approver
     */
    public function removeApprover($employeeId, $approverId)
    {
        try {
            $deleted = Approver::where('employee_id', $employeeId)
                ->where('approver_id', $approverId)
                ->delete();

            if ($deleted) {
                // Reorder hierarchy levels
                $this->reorderHierarchy($employeeId);
                return ['success' => true, 'message' => 'Approver removed successfully'];
            }

            return ['success' => false, 'message' => 'Approver not found'];
        } catch (\Exception $e) {
            Log::error('Error removing approver: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to remove approver'];
        }
    }

    /**
     * Reorder hierarchy levels after deletion
     */
    private function reorderHierarchy($employeeId)
    {
        $approvers = Approver::where('employee_id', $employeeId)
            ->orderBy('hierarchy_level')
            ->get();

        foreach ($approvers as $index => $approver) {
            $approver->update(['hierarchy_level' => $index + 1]);
        }
    }

    /**
     * Get employee details with office info
     */
    public function getEmployeeDetails($employeeId)
    {
        return Employee::select('id', 'full_name', 'epf_number', 'office_location')
            ->with('office')
            ->find($employeeId);
    }
}
