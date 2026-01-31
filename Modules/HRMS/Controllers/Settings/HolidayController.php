<?php

namespace Modules\HRMS\Controllers\Settings;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\HRMS\Models\Settings\Holiday;
use Modules\HRMS\Services\Settings\HolidayService;
use Illuminate\Http\Request;

class HolidayController extends Controller
{

    /**
     * Service variable
     *
     * @var HolidayService
     */
    private $service; 
    function __construct(HolidayService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['holidays'] = $this->service->getAll();

        return view("HRMS::settings.holidays.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return view('HRMS::settings.holidays.create', compact('days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'name' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'every_year' => 'nullable|boolean',
            'holiday_day_type' => 'nullable|integer',
            'day' => 'nullable'
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.settings.holidays.index')->with('success', 'Holiday created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['holiday'] = $this->service->show($id);

        return view("holidays.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holiday $holiday)
    {
        $data['holiday'] = $holiday;
        $data['days'] = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return view("HRMS::settings.holidays.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Holiday $holiday)
    {
        $validate = $request->validate([
            'name' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'every_year' => 'nullable|boolean',
            'holiday_day_type' => 'nullable|integer',
            'day' => 'nullable'
        ]);
        $this->service->update($holiday, $validate);

        return redirect()->route('hrm.settings.holidays.index')->with('success', 'Holiday updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $this->service->delete($holiday);
        return redirect()->route('hrm.settings.holidays.index')->with('success', 'Holiday deleted successfully.');
    }
}
