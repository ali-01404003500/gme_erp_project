<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Services\LeaveYearService;

class LeaveYearController extends Controller
{
    protected $leaveYearService;

    public function __construct(LeaveYearService $leaveYearService)
    {
        $this->leaveYearService = $leaveYearService;
    }

    public function index()
    {
        $runningYear = $this->leaveYearService->getRunningYear();
        $closedYears = $this->leaveYearService->getClosedYears();

        return view('HRMS::leave-year.index', compact('runningYear', 'closedYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year'       => 'required|digits:4|unique:leave_years,year',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        try {
            $this->leaveYearService->storeYear($request->all());
            return redirect()->back()->with('success', 'New Leave Year opened successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
