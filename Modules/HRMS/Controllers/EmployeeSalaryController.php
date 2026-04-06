<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\SalaryBreakdown;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSalary;
use Modules\HRMS\Models\Settings\SalarySetup;
use Modules\HRMS\Services\EmployeeSalaryService;
use Illuminate\Http\Request;

class EmployeeSalaryController extends Controller
{

    /**
     * Service variable
     *
     * @var EmployeeSalaryService
     */
    private $service; 
    function __construct(EmployeeSalaryService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['employeeSalaries'] = $this->service->getAll();

        return view("HRMS::employee-salarys.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employee_id = $request->query('employee_id');
        $salary_id = $request->query('salary_id'); // New parameter to check for edit

        $data['employee'] = Employee::findOrFail($employee_id);
        $data['salaryBreakdown'] = SalaryBreakdown::where('status',1)->get();
        $data['salarySetups'] = SalarySetup::where('status', 1)->get();

        if ($salary_id) {
            $data['employeeSalary'] = EmployeeSalary::find($salary_id);
            // dd($data['salarySetups']);

        } else {
            $data['employeeSalary'] = new EmployeeSalary(); // Empty object for new entry
        }

        $data['employeeSalaries'] = EmployeeSalary::where('employee_id', $employee_id)->orderBy('id', 'desc')->get();
        return view('HRMS::employee-salarys.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'effective_date' => 'required|date',
            'salary_setup_id' => 'nullable|integer|exists:salary_setups,id',
            'is_consolidate' => 'required|numeric|in:0,1', 
            'basic' => 'required|numeric|min:1',
            'house_rent' => 'nullable|numeric',
            'medical' => 'nullable|numeric',
            'conveyance' => 'nullable|numeric',
            'entertainment' => 'nullable|numeric',
            'leave_fare' => 'nullable|numeric',
            'utility' => 'nullable|numeric',
            'unkeep' => 'nullable|numeric',
            'others' => 'nullable|numeric',
            'increase_basic' => 'nullable|numeric',
            'increase_house_rent' => 'nullable|numeric',
            'increase_medical' => 'nullable|numeric',
            'increase_conveyance' => 'nullable|numeric',
            'increase_entertainment' => 'nullable|numeric',
            'increase_leave_fare' => 'nullable|numeric',
            'increase_utility' => 'nullable|numeric',
            'increase_unkeep' => 'nullable|numeric',
            'increase_others' => 'nullable|numeric',
            'gross' => 'required|numeric|min:1',
            'tax' => 'nullable|numeric',
            'payment_type' => 'required|in:bank,cash',
            
        ]); 

        $employeeId = $validate['employee_id'];
        $effectiveFrom = $validate['effective_date'];

        // Check if same employee already has salary with this date
        $existsQuery = EmployeeSalary::where('employee_id', $employeeId)
            ->where('effective_date', $effectiveFrom);
 
        $existing = $existsQuery->first();

        if($existing){
            return redirect()->back()
                ->withInput()
                ->withErrors(['effective_from' => 'Salary entry for this employee with this Effective From date already exists!']);
        }


        $result = $this->service->store($validate); 
        return redirect()->route('hrm.employee-salarys.create', ['employee_id' => $validate['employee_id'],'salary_id' => $result->id])->with('success', 'EmployeeSalary updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['employeeSalary'] = $this->service->show($id);

        return view("employeeSalarys.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeSalary $employeeSalary)
    {
        $data['employeeSalary'] = $employeeSalary;
        //
        return view("employeeSalarys.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'effective_date' => 'required|date',
            'salary_setup_id' => 'nullable|integer|exists:salary_setups,id',
            'is_consolidate' => 'required|numeric|in:0,1', 
            'basic' => 'required|numeric|min:1',
            'house_rent' => 'nullable|numeric',
            'medical' => 'nullable|numeric',
            'conveyance' => 'nullable|numeric',
            'entertainment' => 'nullable|numeric',
            'leave_fare' => 'nullable|numeric',
            'utility' => 'nullable|numeric',
            'unkeep' => 'nullable|numeric',
            'others' => 'nullable|numeric',
            'increase_basic' => 'nullable|numeric',
            'increase_house_rent' => 'nullable|numeric',
            'increase_medical' => 'nullable|numeric',
            'increase_conveyance' => 'nullable|numeric',
            'increase_entertainment' => 'nullable|numeric',
            'increase_leave_fare' => 'nullable|numeric',
            'increase_utility' => 'nullable|numeric',
            'increase_unkeep' => 'nullable|numeric',
            'increase_others' => 'nullable|numeric', 
            'gross' => 'required|numeric|min:1',
            'tax' => 'nullable|numeric',
            'payment_type' => 'required|in:bank,cash',
        ]); 

        $employeeId = $validate['employee_id'];
        $effectiveFrom = $validate['effective_date'];

        // Check if same employee already has salary with this date
        $existsQuery = EmployeeSalary::where('employee_id', $employeeId)
            ->where('effective_date', $effectiveFrom);

        // if editing, ignore current row
        if($id){
            $existsQuery->where('id', '!=', $id);
        }

        $existing = $existsQuery->first();

        if($existing){
            return redirect()->back()
                ->withInput()
                ->withErrors(['effective_date' => 'Salary entry for this employee with this Effective From date already exists!']);
        }

        
        // Old salary deactivate
        $oldSalary = EmployeeSalary::findOrFail($id);
        $oldSalary->status = 0;  // deactivate
        $oldSalary->save();

        $this->service->store($validate);
      
        return redirect()->route('hrm.employee-salarys.create', ['employee_id' => $validate['employee_id'],'salary_id' => $id])->with('success', 'EmployeeSalary updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeSalary $employeeSalary)
    {
        $this->service->delete($employeeSalary);
        return redirect()->route('hrm.employee-salarys.create', ['employee_id' => $employeeSalary->employee_id])->with('success', 'EmployeeSalary deleted successfully.');
    }
}
