<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\SalarySignatory;

class SalarySignatoryController extends Controller
{
    public function index()
    {
        $data['signatories'] = SalarySignatory::with('employee')
            ->orderBy('approver_level', 'asc')
            ->get();

        $data['employees'] = Employee::where('status', 1)->get();

        return view('HRMS::salary-signatories.index', $data);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function edit($id)
    {
        $data['salarySignatory'] = SalarySignatory::findOrFail($id);

        $data['employees'] = Employee::where('status', 1)->get();

        return view('HRMS::salary-signatories.edit', $data);
    }

    public function update(Request $request, SalarySignatory $salarySignatory)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required|exists:employees,id,' . $salarySignatory->id,
            'signatory_tag' => 'required|string|max:100|unique:salary_signatories,signatory_tag,' . $salarySignatory->id,
            'status'        => 'required|in:active,inactive',
            'description'   => 'nullable|string|max:500',
        ]);

        $salarySignatory->update($request->all());

        return redirect()->route('hrm.salary-signatories.index')
            ->with('success', 'Salary signatory updated successfully');
    }

    public function destroy(SalarySignatory $salarySignatory)
    {
        if ($salarySignatory->approvalDetails()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete signatory with existing approval records');
        }

        $salarySignatory->delete();

        return redirect()->route('hrm.salary-signatories.index')
            ->with('success', 'Salary signatory deleted successfully');
    }

}
