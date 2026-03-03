<?php

namespace Modules\Account\Controllers\Payments;

use App\Http\Controllers\Controller;
use Modules\Account\Models\LoanPayment;
use Modules\HRMS\Services\LoanService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Employee;

class LoanPaymentController extends Controller
{

    /**
     * Service variable
     *
     * @var LoanPaymentService
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
        $data['loans'] = $this->service->getOnlyApproved();
        $data['employees'] = Employee::select(['id','full_name'])->get();

        return view('Account::payments.loan-payments.index', $data);
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function payment($id)
    {  
        DB::beginTransaction(); 
        $loan = $this->service->show($id);

        $loan->update([ 
            'payment_date' =>  now(),
        ]);

        $cashAccount = auth()->user()->employee->getCashAccount();
        $loan->paymentDetails()->create([
            'pay_mode' => 'Cash',
            'bank_id' =>  $cashAccount->id?? null,
            'amount' => $loan->amount,
            'date' => now()->format('Y-m-d'),
            'verified' => 0, 
            'remark' => $loan->remarks ?? null,
        ]);
 
        //$this->service->makeDummyTransaction($loan);

        DB::commit();
        return redirect()->route('account.payments.loan-payment.index')->with('success', 'LoanPayment payment successfully.');


    }



    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     return view('loan-payment.create');
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show( $id)
    // {
    //     $data['loanPayment'] = $this->service->show($id);

    //     return view("loanPayments.show", $data);
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(LoanPayment $loanPayment)
    // {
    //     $data['loanPayment'] = $loanPayment;
    //     //
    //     return view("loanPayments.edit", $data);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, LoanPayment $loanPayment)
    // {
    //     $validate = $request->validate([
    //         //validate rules
    //     ]);
    //     $this->service->update($loanPayment, $validate);

    //     return redirect()->route('loanPayments.index')->with('success', 'LoanPayment updated successfully.');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(LoanPayment $loanPayment)
    // {
    //     $this->service->delete($loanPayment);
    //     return redirect()->route('loanPayments.index')->with('success', 'LoanPayment deleted successfully.');
    // }
}
