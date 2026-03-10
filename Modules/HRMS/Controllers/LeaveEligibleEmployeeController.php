<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveEligibleEmployeeController extends Controller
{
    public function index()
    {

        $eligibilities = [
            ['id' => 1, 'condition_type' => 'Job Base', 'eligibility' => 'Permanent'],
            ['id' => 2, 'condition_type' => 'Job Base', 'eligibility' => 'Contractual'],
            ['id' => 3, 'condition_type' => 'Job Base', 'eligibility' => 'Probation'],
            
        ];

        return view('HRMS::leave-eligible-employee.index', compact('eligibilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'condition_type' => 'required',
            'eligibility'    => 'required',
        ]);

        return redirect()->back()->with('success', 'Eligibility added successfully.');
    }
}
