<?php

namespace Modules\HRMS\Controllers\Settings;
use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Settings\LeaveType;
use Modules\HRMS\Services\Settings\LeaveTypeService;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var LeaveTypeService
     */
    private $service; 
    function __construct(LeaveTypeService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['leaveTypes'] = $this->service->getAll();

        return view("HRMS::settings.leave-types.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leaveTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validate = $request->validate([
            'leave_type_name' => 'required|string|max:255|unique:leave_types,leave_type_name,NULL,id,deleted_at,NULL',
            'payment_mode' => 'required|in:with_pay,without_pay',
            'total_day' => 'required|integer',
            'simultaneously_limit' => 'required|integer',
        ]);
        // dd($validate);
        $this->service->store($validate);

        return redirect()->route('hrm.settings.leave-types.index')->with('success', 'LeaveType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['leaveType'] = $this->service->show($id);

        return view("leaveTypes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveType $leaveType)
    {
        $data['leaveType'] = $leaveType;
        //
        return view("leaveTypes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $validate = $request->validate([
            'leave_type_name' => 'required|string|max:255',
            'payment_mode' => 'required|in:with_pay,without_pay',
            'total_day' => 'required|integer',
            'simultaneously_limit' => 'required|integer',
        ]);
        $this->service->update($leaveType, $validate);

        return redirect()->route('hrm.settings.leave-types.index')->with('success', 'LeaveType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leaveType)
    {
        $this->service->delete($leaveType);
        return redirect()->route('hrm.settings.leave-types.index')->with('success', 'LeaveType deleted successfully.');
    }
}
