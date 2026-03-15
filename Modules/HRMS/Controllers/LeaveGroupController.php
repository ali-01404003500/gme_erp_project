<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\LeaveGroup;
use Modules\HRMS\Models\Settings\LeaveType;
use Modules\HRMS\Services\LeaveGroupService;

class LeaveGroupController extends Controller
{

    protected $leaveGroupService;

    public function __construct(LeaveGroupService $leaveGroupService)
    {
        $this->leaveGroupService = $leaveGroupService;
    }

    public function index()
    {
        $leaveTypes  = LeaveType::orderBy('leave_type_name', 'asc')->get();
        $leaveGroups = $this->leaveGroupService->getAllGroups();
        // $leaveGroups = LeaveGroup::with('leaveTypes')->withCount('employees')->latest()->get();
        return view('HRMS::leave-group.index', compact('leaveTypes', 'leaveGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'configs'    => 'required|array',
        ]);

        try {
            $this->leaveGroupService->storeGroup($request->all());
            return redirect()->back()->with('success', 'Leave Group added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'configs'    => 'required|array',
        ]);

        try {
            $this->leaveGroupService->updateGroup($id, $request->all());
            return redirect()->back()->with('success', 'Leave Group updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->leaveGroupService->deleteGroup($id);
            return redirect()->back()->with('success', 'Leave Group deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }
}
