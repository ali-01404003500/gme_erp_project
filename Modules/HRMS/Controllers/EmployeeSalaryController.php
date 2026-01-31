<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
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
            'basic' => 'required|numeric|min:1',
            'house_rent' => 'nullable|numeric',
            'conveyance' => 'nullable|numeric',
            'medical' => 'nullable|numeric',
            'others' => 'nullable|numeric',
            'gross' => 'required|numeric|min:1',
            'tax' => 'nullable|numeric',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.employee-salarys.create', ['employee_id' => $validate['employee_id']])->with('success', 'EmployeeSalary created successfully.');
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
        'basic' => 'required|numeric|min:1',
        'house_rent' => 'nullable|numeric',
        'conveyance' => 'nullable|numeric',
        'medical' => 'nullable|numeric',
        'others' => 'nullable|numeric',
        'gross' => 'required|numeric|min:1',
        'tax' => 'nullable|numeric',
    ]);

    $employeeSalary = EmployeeSalary::findOrFail($id);
    $employeeSalary->update($validate);

    return redirect()->route('hrm.employee-salarys.create', ['employee_id' => $validate['employee_id']])->with('success', 'EmployeeSalary updated successfully.');
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
