<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\HRMS\Models\AttendancePolicy;
use Modules\HRMS\Services\AttendancePolicyService;

class AttendancePolicyController extends Controller
{
    protected $service;

    public function __construct(AttendancePolicyService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the attendance policies.
     */
    public function index(Request $request)
    {
        try {
            $search   = $request->input('search');
            $policies = $this->service->getAllPolicies($search);

            return view('HRMS::settings.attendance-policies.index', compact('policies'));
        } catch (Exception $e) {
            Log::error("Error loading Attendance Policies: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load policies.');
        }
    }

    /**
     * Show the form for creating a new policy.
     */
    public function create()
    {
        return view('HRMS::settings.attendance-policies.create');
    }

    /**
     * Store a newly created policy in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'effective_from' => 'required|date',
        ]);

        try {
            $this->service->storePolicy($request->all());
            return redirect()->route('hrm.settings.attendance-policies.index')
                ->with('success', 'Attendance Policy created successfully.');
        } catch (Exception $e) {
            Log::error("Attendance Policy Store Error: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Something went wrong while saving.');
        }
    }

    /**
     * Show the form for editing the specified policy.
     */
    public function edit($id)
    {
        try {
            $policy = AttendancePolicy::findOrFail($id);
            return view('HRMS::settings.attendance-policies.edit', compact('policy'));
        } catch (Exception $e) {
            return redirect()->route('hrm.settings.attendance-policies.index')->with('error', 'Policy not found.');
        }
    }

    /**
     * Update the specified policy in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'effective_from' => 'required|date',
        ]);

        try {
            $this->service->updatePolicy($id, $request->all());
            return redirect()->route('hrm.settings.attendance-policies.index')
                ->with('success', 'Attendance Policy updated successfully.');
        } catch (Exception $e) {
            Log::error("Attendance Policy Update Error: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update policy.');
        }
    }

    /**
     * Remove the specified policy from storage.
     */
    public function destroy($id)
    {
        try {
            $policy = AttendancePolicy::findOrFail($id);
            $policy->delete();
            return redirect()->back()->with('success', 'Deleted successfully!');
        } catch (Exception $e) {
            Log::error("Attendance Policy Delete Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete policy.');
        }
    }
}
