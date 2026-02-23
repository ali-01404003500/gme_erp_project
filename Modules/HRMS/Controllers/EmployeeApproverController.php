<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ApproverService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\HRMS\Models\Approver;
use Modules\HRMS\Models\Employee as ModelsEmployee;
use Modules\HRMS\Services\ApproverService as ServicesApproverService;
use Modules\HRMS\Models\Employee;
use Illuminate\Support\Facades\DB;


class EmployeeApproverController extends Controller
{
    protected $approverService;

    public function __construct(ServicesApproverService $approverService)
    {
        $this->approverService = $approverService;
    }

    /**
     * Show the approver management page
     */
    public function index(Request $request)
    {
        $employeeId = $request->get('employee_id');
        $employee = null;
        $currentApprovers = [];
        $hasEmployeeSelected = false;

        if ($employeeId) {
            $employee = Employee::find($employeeId);
            if ($employee) {
                $hasEmployeeSelected = true;
                $currentApprovers = Approver::with('approver')
                    ->where('employee_id', $employeeId)
                    ->orderBy('hierarchy_level')
                    ->get();
            }
        }

        // Get all employees for dropdown
        $employees = Employee::select('id', 'full_name', 'epf_number')
            ->where('status', 1)
            ->orderBy('full_name')
            ->get();

        return view('HRMS::settings.approver-setup.index', compact(
            'employees', 
            'employee', 
            'currentApprovers',
            'hasEmployeeSelected',
            'employeeId'
        ));
    }

    /**
     * Search available approvers (AJAX)
     */
    public function searchApprovers(Request $request)
    {
     try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'search' => 'nullable|string|max:255'
            ]);

            $searchTerm = $request->search;
            
            // Get existing approver IDs for this employee
            $existingApproverIds = Approver::where('employee_id', $request->employee_id)
                ->pluck('approver_id')
                ->toArray();

            // Search for employees excluding self and existing approvers
            $query = Employee::where('id', '!=', $request->employee_id)
                ->where('status', 1)
                ->whereNotIn('id', $existingApproverIds);

            if ($searchTerm) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('full_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('epf_number', 'LIKE', "%{$searchTerm}%");
                });
            }

            $results = $query->select('id', 'full_name', 'epf_number', 'designation')
                ->limit(20)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'full_name' => $item->full_name,
                        'epf_number' => $item->epf_number ?? 'N/A',
                        'designation' => $item->designation ?? 'N/A'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add approvers to employee
     */
    public function store(Request $request)
    {
      try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'approver_ids' => 'required|array|min:1',
                'approver_ids.*' => 'exists:employees,id|different:employee_id'
            ]);

            DB::beginTransaction();

            // Get current max hierarchy level
            $currentMaxLevel = Approver::where('employee_id', $request->employee_id)
                ->max('hierarchy_level') ?? 0;

            // Prepare data for insertion
            $approvers = [];
            foreach ($request->approver_ids as $index => $approverId) {
                // Check if already exists
                $exists = Approver::where('employee_id', $request->employee_id)
                    ->where('approver_id', $approverId)
                    ->exists();

                if (!$exists) {
                    $approvers[] = [
                        'employee_id' => $request->employee_id,
                        'approver_id' => $approverId,
                        'hierarchy_level' => $currentMaxLevel + $index + 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            if (!empty($approvers)) {
                Approver::insert($approvers);
            }

            DB::commit();

            // Get updated approvers list
            $currentApprovers = Approver::with('approver')
                ->where('employee_id', $request->employee_id)
                ->orderBy('hierarchy_level')
                ->get();

            $html = view('HRMS::settings.approver-setup.approval-table', compact('currentApprovers'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Approvers added successfully',
                'data' => $html
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add approvers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove approver
     */
    public function destroy(Request $request)
    {
       try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'approver_id' => 'required|exists:employees,id'
            ]);

            DB::beginTransaction();

            Approver::where('employee_id', $request->employee_id)
                ->where('approver_id', $request->approver_id)
                ->delete();

            // Reorder hierarchy
            $this->reorderHierarchy($request->employee_id);

            DB::commit();

            // Get updated approvers list
            $currentApprovers = Approver::with('approver')
                ->where('employee_id', $request->employee_id)
                ->orderBy('hierarchy_level')
                ->get();

            $html = view('HRMS::settings.approver-setup.approval-table', compact('currentApprovers'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Approver removed successfully',
                'data' => $html
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove approver'
            ], 500);
        }
    }

    /**
     * Get current approvers for employee (AJAX)
     */
    public function getCurrentApprovers($employeeId)
    {
         try {
            $currentApprovers = Approver::with('approver')
                ->where('employee_id', $employeeId)
                ->orderBy('hierarchy_level')
                ->get();

            $html = view('HRMS::settings.approver-setup.approval-table', compact('currentApprovers'))->render();

            return response()->json([
                'success' => true,
                'data' => $html
            ]);

        } catch (\Exception $e) {
            Log::error('Get current error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load approvers'
            ], 500);
        }
    }

    /**
     * Reorder hierarchy levels
     */
    private function reorderHierarchy($employeeId)
    {
        $approvers = Approver::where('employee_id', $employeeId)
            ->orderBy('hierarchy_level')
            ->get();

        foreach ($approvers as $index => $approver) {
            $approver->hierarchy_level = $index + 1;
            $approver->save();
        }
    }
}