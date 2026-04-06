<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\LeaveEligibleEmployee;

class LeaveEligibleEmployeeController extends Controller
{
    public function index()
    {
        $eligibilities = LeaveEligibleEmployee::latest()->get();
        return view('HRMS::leave-eligible-employee.index', compact('eligibilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'condition_type' => 'required',
            'eligibility'    => 'required',
        ]);

        LeaveEligibleEmployee::create($request->all());
        return redirect()->back()->with('success', 'Data saved successfully!');
    }

    public function edit($id)
    {
        $item = LeaveEligibleEmployee::findOrFail($id);
        return view('HRMS::leave-eligible-employee.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'condition_type' => 'required',
            'eligibility'    => 'required',
        ]);

        $item = LeaveEligibleEmployee::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('hrm.leave-eligible-employees.index')->with('success', 'Updated successfully!');
    }

    public function destroy($id)
    {
        LeaveEligibleEmployee::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');
    }
}
