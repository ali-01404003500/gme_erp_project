<?php

namespace Modules\HRMS\Controllers\Settings;

use App\Http\Controllers\Controller;
use Modules\HRMS\Services\Settings\SupervisorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;


class SupervisorController extends Controller
{
    protected $supervisorService;

    public function __construct(SupervisorService $supervisorService)
    {
        $this->supervisorService = $supervisorService;
    }

    public function index(): View
    {
        $supervisors = $this->supervisorService->getAllSupervisors();
        return view('HRMS::settings.approver.index', compact('supervisors'));
    }

    public function create(): View
    {
        return view('supervisors.create');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_code' => 'required|string|unique:supervisors',
            'employee_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'office_location' => 'required|string|max:255',
            'hierarchy_level' => 'required|integer|min:1'
        ]);

        try {
            $supervisor = $this->supervisorService->createSupervisor($validated);
            return response()->json([
                'success' => true,
                'message' => 'Supervisor added successfully',
                'data' => $supervisor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add supervisor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id): View
    {
        $supervisor = $this->supervisorService->getSupervisorById($id);
        return view('HRMS::approver.edit', compact('supervisor'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'employee_code' => 'required|string|unique:supervisors,employee_code,' . $id,
            'employee_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'office_location' => 'required|string|max:255',
            'hierarchy_level' => 'required|integer|min:1'
        ]);

        try {
            $supervisor = $this->supervisorService->updateSupervisor($id, $validated);
            return response()->json([
                'success' => true,
                'message' => 'Supervisor updated successfully',
                'data' => $supervisor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update supervisor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->supervisorService->deleteSupervisor($id);
            return response()->json([
                'success' => true,
                'message' => 'Supervisor deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete supervisor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function searchEmployees(Request $request): JsonResponse
    {
        $searchTerm = $request->get('q', '');
        $employees = $this->supervisorService->searchEmployees($searchTerm);
        
        return response()->json($employees);
    }
}