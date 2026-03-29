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
        $signatories = SalarySignatory::with('employee')
            ->orderBy('level', 'asc')
            ->get();

        return view('HRMS::salary-signatories.index', compact('signatories'));
    }

    public function create()
    {
        $employees = Employee::select('id', 'full_name') 
            ->where('status', 1)
            ->whereDoesntHave('salarySignatory')
            ->whereHas('employementDetail', function ($q) {
                $q->whereIn('department_id', [5, 6])
                    ->where('status', 1);
            })
            ->orderBy('full_name', 'asc')
            ->get();

        $levels = $this->getAvailableLevels();

        return view('HRMS::salary-signatories.create', compact('employees', 'levels'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required|exists:employees,id|unique:salary_signatories,employee_id',
            'signatory_tag' => 'required|string|max:100|unique:salary_signatories,signatory_tag',
            'level'         => 'required|integer|min:1|unique:salary_signatories,level',
            'status'        => 'required|in:active,inactive',
            'description'   => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SalarySignatory::create($request->all());

        return redirect()->route('hrm.salary-signatories.index')
            ->with('success', 'Salary signatory created successfully');
    }

    public function edit(SalarySignatory $salarySignatory)
    {
        $employees = Employee::where(function ($query) use ($salarySignatory) {
            $query->whereDoesntHave('salarySignatory')
                ->orWhere('id', $salarySignatory->employee_id);
        })
            ->orderBy('id')
            ->get();

        $levels = $this->getAvailableLevels($salarySignatory->id);

        return view('HRMS::salary-signatories.edit', compact('salarySignatory', 'employees', 'levels'));
    }

    public function update(Request $request, SalarySignatory $salarySignatory)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required|exists:employees,id|unique:salary_signatories,employee_id,' . $salarySignatory->id,
            'signatory_tag' => 'required|string|max:100|unique:salary_signatories,signatory_tag,' . $salarySignatory->id,
            'level'         => 'required|integer|min:1|unique:salary_signatories,level,' . $salarySignatory->id,
            'status'        => 'required|in:active,inactive',
            'description'   => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

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

    private function getAvailableLevels($excludeId = null)
    {
        $usedLevels = SalarySignatory::when($excludeId, function ($query) use ($excludeId) {
            $query->where('id', '!=', $excludeId);
        })->pluck('level')->toArray();

        $allLevels = range(1, 10);
        return array_diff($allLevels, $usedLevels);
    }
}
