<?php
namespace Modules\HRMS\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Settings\LeaveType;
use Modules\HRMS\Services\Settings\LeaveTypeService;

class LeaveTypeController extends Controller
{
    private $service;

    public function __construct(LeaveTypeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data['leaveTypes'] = $this->service->getAll();
        return view("HRMS::settings.leave-types.index", $data);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'leave_type_name'      => 'required|string|max:255|unique:leave_types,leave_type_name,NULL,id,deleted_at,NULL',
            'flag'                 => 'required|string',
            'half_flag'            => 'required|string',
            'total_day'            => 'required|integer|min:0',
            'simultaneously_limit' => 'required|integer|min:0',
            'leave_count_type'     => 'required|in:day,hour',
            'is_maternity'         => 'nullable|boolean',
            'is_unpaid'            => 'nullable|boolean',
            'is_partially_balance' => 'nullable|boolean',
        ]);

        $validate['is_maternity']         = $request->has('is_maternity') ? 1 : 0;
        $validate['is_unpaid']            = $request->has('is_unpaid') ? 1 : 0;
        $validate['is_partially_balance'] = $request->has('is_partially_balance') ? 1 : 0;

        $validate['payment_mode'] = $request->has('is_unpaid') ? 'without_pay' : 'with_pay';

        $this->service->store($validate);

        return redirect()->route('hrm.settings.leave-types.index')->with('success', 'Leave Type created successfully.');
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $validate = $request->validate([
            'leave_type_name'      => 'required|string|max:255',
            'flag'                 => 'required|string|max:50',
            'half_flag'            => 'required|string|max:50',
            'total_day'            => 'required|integer|min:0',
            'simultaneously_limit' => 'required|integer|min:0',
            'leave_count_type'     => 'required|in:day,hour',
            'is_maternity'         => 'nullable|boolean',
            'is_unpaid'            => 'nullable|boolean',
            'is_partially_balance' => 'nullable|boolean',
        ]);

        $validate['is_maternity']         = $request->has('is_maternity') ? 1 : 0;
        $validate['is_unpaid']            = $request->has('is_unpaid') ? 1 : 0;
        $validate['is_partially_balance'] = $request->has('is_partially_balance') ? 1 : 0;

        $validate['payment_mode'] = $request->has('is_unpaid') ? 'without_pay' : 'with_pay';

        $this->service->update($leaveType, $validate);

        return redirect()->route('hrm.settings.leave-types.index')->with('success', 'Leave Type updated successfully.');
    }

    public function destroy(LeaveType $leaveType)
    { 
        try {
            $leaveType->delete();
            return redirect()->route('hrm.settings.leave-types.index')->with('success', 'Leave Type deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database Error: ' . $e->getMessage());
        }
    }
}
