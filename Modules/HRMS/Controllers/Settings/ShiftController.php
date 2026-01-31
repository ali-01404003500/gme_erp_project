<?php

namespace Modules\HRMS\Controllers\Settings;
use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Settings\Shift;
use Modules\HRMS\Services\Settings\ShiftService;
use Illuminate\Http\Request;

class ShiftController extends Controller
{

    /**
     * Service variable
     *
     * @var ShiftService
     */
    private $service; 
    function __construct(ShiftService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['shifts'] = $this->service->getAll();

        return view("HRMS::settings.shifts.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shifts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'shift_name' => 'required|string|max:255|unique:shifts,shift_name,NULL,id,deleted_at,NULL',
            'grace_time' => 'required|numeric',
            'in_time' => 'required|date_format:H:i',
            'out_time' => 'required|date_format:H:i',
            'status' => 'required|boolean',
        ]);
        $this->service->store($validate);
        
        return redirect()->route('hrm.settings.shifts.index')->with('success', 'Shift created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['shift'] = $this->service->show($id);

        return view("shifts.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        $data['shift'] = $shift;
        //
        return view("shifts.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shift $shift)
    {
        $validate = $request->validate([
            'shift_name' => 'required|string|max:255',
            'grace_time' => 'required|numeric',
            'in_time' => 'required|date_format:H:i',
            'out_time' => 'required|date_format:H:i',
            'status' => 'required|boolean',
        ]);
        $this->service->update($shift, $validate);

        return redirect()->route('hrm.settings.shifts.index')->with('success', 'Shift updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        $this->service->delete($shift);
        return redirect()->route('hrm.settings.shifts.index')->with('success', 'Shift deleted successfully.');
    }
}
