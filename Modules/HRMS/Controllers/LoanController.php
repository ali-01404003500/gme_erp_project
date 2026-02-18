<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Loan;
use Modules\HRMS\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Employee;

class LoanController extends Controller
{
    /**
     * Service variable
     *
     * @var LoanService
     */
    private $service;
    function __construct(LoanService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['loans'] = $this->service->getAll();
        $data['employees'] = Employee::all();

        return view('HRMS::loans.index', $data);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['employees'] = Employee::all();
        return view('HRMS::loans.create', $data);
    }
    public function loanPayment()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required',
            'payment_date' => 'required|date',
            'start_month' => 'required|date_format:Y-m',
            'monthly_reduction' => 'required',
            'duration' => 'required|integer|min:1',
            'remaining_balance' => 'required',
        ]);

        // Check if employee already has a loan that overlaps with this start month
        $startMonth = $validate['start_month'];
        $duration = $validate['duration'];

        $endMonth = date('Y-m', strtotime('+' . ($duration - 1) . ' months', strtotime($startMonth)));

        $existingLoan = Loan::where('employee_id', $validate['employee_id'])
            ->where(function ($query) use ($startMonth, $endMonth) {
                $query->whereBetween('start_month', [$startMonth, $endMonth])->orWhereBetween(DB::raw('DATE_ADD(start_month, INTERVAL duration-1 MONTH)'), [$startMonth, $endMonth]);
            })
            ->first();

        if ($existingLoan) {
            // Only use `withInput()` without passing $request->all()
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Employee already has a loan overlapping with this period.');
        }


        $this->service->store($validate);

        return redirect()->route('hrm.loans.index')->with('success', 'Loan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['loan'] = $this->service->show($id);

        return view('loans.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        $data['loan'] = $loan;
        $data['employees'] = Employee::all();
        //
        return view('HRMS::loans.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required',
            'payment_date' => 'required|date',
            'start_month' => 'required|date_format:Y-m',
            'monthly_reduction' => 'required',
            'duration' => 'required|integer|min:1',
            'remaining_balance' => 'required',
        ]);
        $this->service->update($loan, $validate);

        return redirect()->route('hrm.loans.index')->with('success', 'Loan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        $this->service->delete($loan);
        return redirect()->route('hrm.loans.index')->with('success', 'Loan deleted successfully.');
    }

    public function approve($id)
    {
        $plan = Loan::findOrFail($id);
        $plan->approved_by = auth()->user()->id;
        $plan->status = 'approved'; // Assuming 1 is for approved
        $plan->save();

        return redirect()->route('hrm.loans.index')->with('success', 'Loan approved successfully.');
    }

    public function deny($id)
    {
        $plan = Loan::findOrFail($id);
        $plan->approved_by = auth()->user()->id;
        $plan->status = 'deny'; // Assuming 2 is for denied
        $plan->save();

        return redirect()->route('hrm.loans.index')->with('warning', 'Loan denied successfully.');
    }

    public function ajaxDetails($id)
    {
        $loan = Loan::with(['employee.employementDetail.designation', 'details'])->findOrFail($id);
        // dd($loan);
        return view('HRMS::loans.partials.modal-details', compact('loan'));
    }
}
