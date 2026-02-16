<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\LeaveAdjustment;
use Modules\HRMS\Services\LeaveAdjustmentService;
use Illuminate\Http\Request;

class LeaveAdjustmentController extends Controller
{

    /**
     * Service variable
     *
     * @var LeaveAdjustmentService
     */
    private $service; 
    function __construct(LeaveAdjustmentService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data['leaveAdjustments'] = $this->service->getAll();

        return view("HRMS::leave-adjustment.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('HRMS::leave-adjustment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->store($validate);
        return redirect()->route('leaveAdjustments.index')->with('success', 'LeaveAdjustment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['leaveAdjustment'] = $this->service->show($id);

        return view("leaveAdjustments.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveAdjustment $leaveAdjustment)
    {
        $data['leaveAdjustment'] = $leaveAdjustment;
        //
        return view("leaveAdjustments.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveAdjustment $leaveAdjustment)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($leaveAdjustment, $validate);

        return redirect()->route('leaveAdjustments.index')->with('success', 'LeaveAdjustment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveAdjustment $leaveAdjustment)
    {
        $this->service->delete($leaveAdjustment);
        return redirect()->route('leaveAdjustments.index')->with('success', 'LeaveAdjustment deleted successfully.');
    }
}
