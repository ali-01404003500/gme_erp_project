<?php

namespace Modules\HRMS\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\PDF;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSalary;
use Modules\HRMS\Models\SalaryGenerate;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Services\SalaryGenerateService;
use Illuminate\Http\Request;

class SalaryGenerateController extends Controller
{

    /**
     * Service variable
     *
     * @var SalaryGenerateService
     */
    private $service; 
    function __construct(SalaryGenerateService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
        $data['salaryGenerates'] = $this->service->getAll();
        $data['departments'] = Department::where('status', 1)->get();
        $data['employees'] = Employee::all();
        return response()->json([
                'data' => $data,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('salaryGenerates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $salaries = EmployeeSalary::whereRaw("DATE_FORMAT(effective_date, '%Y-%m') <= ?", $request->year_month)
            ->latest('effective_date')
            ->whereHas('employee', function ($q) use ($request) {
                $q->whereDoesntHave('salaryGenerates', function ($q) use ($request) {
                    $q->where('year_month', $request->year_month);
                });
                
                if ($request->department_id != null) {
                    $q->whereHas('employementDetails', function ($q) use ($request) {
                        $q->where('department_id', $request->department_id);
                    });
                }
            })
            ->get()
            ->unique('employee_id');

        $data = [];
        if($salaries->count() == 0) {
            return response()->json(['status' => false, 'message' => 'No employee found.']);
        }
        foreach ($salaries as $salary) {
            $data['employee_id'][] = $salary->employee_id;
            $data['basic'][] = $salary->basic;
            $data['house_rent'][] = $salary->house_rent;
            $data['medical'][] = $salary->medical;
            $data['conveyance'][] = $salary->conveyance;
            $data['others'][] = $salary->others;
            $data['gross'][] = $salary->gross;
            $data['tax'][] = $salary->tax;
            $data['net_earning'][] = $salary->gross - $salary->tax;
            $data['year_month'][] = $request->year_month;
            $data['department_id'][] = $request->department_id ?? null;
            $data['status'][] = 'UnPaid';
        }

        $this->service->store($data, $request);
        return response()->json(['status' => true, 'message' => 'SalaryGenerate created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        try{
        $data['salaryGenerate'] = $this->service->show($id);

        return response()->json([
            'data' => $data,
            'status' => true,
        ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryGenerate $salaryGenerate)
    {
        $data['salaryGenerate'] = $salaryGenerate;
        //
        return view("HRMS::payroll.salary-generates.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryGenerate $salaryGenerate)
    {
        $validate = $request->validate([
           'employee_id' => 'required|integer|exists:employees,id',
            'basic' => 'required|numeric',
            'house_rent' => 'required|numeric',
            'medical' => 'nullable|numeric',
            'conveyance' => 'nullable|numeric',
            'others' => 'nullable|numeric',
            'ot_pay' => 'nullable|numeric',
            'double_time_pay' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
            'bonus' => 'nullable|numeric',
            'leave_encashment' => 'nullable|numeric',
            'advance' => 'nullable|numeric',
            'loan' => 'nullable|numeric',
            'no_pay_leave' => 'nullable|numeric',
            'absence' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'gross' => 'nullable|numeric',
            'total_other_earnings' => 'nullable|numeric',
            'total_earnings' => 'nullable|numeric',
            'total_deductions' => 'nullable|numeric',
            'total_tax' => 'nullable|numeric',
            'net_earning' => 'nullable|numeric',
            'status' => 'required|string',
            'pay_date' => 'nullable|date',

        ]);
        $this->service->update($salaryGenerate, $validate);

        return response()->json(['status' => true, 'message' => 'SalaryGenerate updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryGenerate $salaryGenerate)
    {
        $this->service->delete($salaryGenerate);
        return response()->json(['status' => true, 'message' => 'SalaryGenerate deleted successfully.']);
    }

    public function paid($id)
    {
        $salaryGenerate = SalaryGenerate::find($id);
        $salaryGenerate->status = 'Paid';
        $salaryGenerate->pay_date = date('Y-m-d');

        $salaryGenerate->salaryGeneratePayments()->create([
            'amount' => $salaryGenerate->net_earning,
            'pay_date' => date('Y-m-d'),
        ]);
        $salaryGenerate->save();

        return redirect()->back()->with('success', 'Salary paid successfully');
    }

    public function partiallyPaid(Request $request, $id)
    {
        $salaryGenerate = SalaryGenerate::findOrFail($id);
    
        $salaryGenerate->status = 'Partially Paid';
        $salaryGenerate->pay_date = date('Y-m-d');
        
        // Assuming salaryGeneratePayments is a relation and you want to store the partial payment
        $salaryGenerate->salaryGeneratePayments()->create([
            'amount' => $request->amount,
            'pay_date' => date('Y-m-d'),
        ]);
    
        $salaryGenerate->save();
    
        return redirect()->back()->with('success', 'Salary partially paid successfully.');
    }
    

    public function paidAll(Request $request)
    {
        $ids = $request->input('id');
        $status = $request->input('status');
        
        SalaryGenerate::whereIn('id', $ids)->update([
            'status' => $status,
            'pay_date' => date('Y-m-d')
        ]);
        
        foreach ($ids as $id) {
            $salaryGenerate = SalaryGenerate::find($id);
            
            $totalPaidAmount = $salaryGenerate->salaryGeneratePayments()->sum('amount') ?? 0;
            
            $remainingAmount = $salaryGenerate->net_earning - $totalPaidAmount;
            
            $salaryGenerate->salaryGeneratePayments()->create([
                'amount' => $remainingAmount,
                'pay_date' => date('Y-m-d'),
            ]);
        }
        
        return response()->json(['message' => 'Salaries updated successfully!']);
    }
    

    public function partiallyPaidAll(Request $request)
    {
        $ids = $request->input('id');
        $status = $request->input('status');
        $amount = $request->input('amount');
    
        $salaryGenerate = SalaryGenerate::whereIn('id', $ids)->update([
            'status' => $status,
            'pay_date' => date('Y-m-d'),
        ]);
    
        foreach ($ids as $id) {
            $salaryGeneratePayment = SalaryGenerate::find($id)->salaryGeneratePayments()->create([
                'amount' => $amount,
                'pay_date' => date('Y-m-d'),
            ]);
        }
    
        return response()->json(['message' => 'Salaries updated successfully!']);
    }


    
    public function getMyPayslip(Request $request)
    {
        $salaryGenerateId = $request->input('id');

        $salaryGenerate = SalaryGenerate::with(['employee.employementDetails.designation', 'department'])
        ->where('id', $salaryGenerateId)
        ->first();

        return response()->json([ 'data' => $salaryGenerate, 'status' => true, 'message' => 'Payslip fetched successfully.']);
    }

    //download payslip pdf
    public function downloadPayslip($id){
        $salaryGenerate = SalaryGenerate::with(['employee', 'department'])->find($id);
        $pdf = PDF::loadView('hrms::pdf.payslip', compact('salaryGenerate'));
        return $pdf->stream('payslip.pdf');
    }


    public function myPayslips(){
        $employeeId = auth()?->user()?->employee?->id;
        $salaryGenerates = SalaryGenerate::with(['employee.employementDetails.designation', 'department'])->where('employee_id', $employeeId)->get();
        return response()->json([ 'data' => $salaryGenerates, 'status' => true, 'message' => 'Payslip fetched successfully.']);
    }
}
