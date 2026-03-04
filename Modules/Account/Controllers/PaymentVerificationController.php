<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use Modules\Account\Models\PaymentVerification;
use Modules\Account\Services\PaymentVerificationService;
use Illuminate\Http\Request;
use Modules\Account\Models\Payments\MakePaymentDetail;

class PaymentVerificationController extends Controller
{

    /**
     * Service variable
     *
     * @var PaymentVerificationService
     */
    private $service; 
    function __construct(PaymentVerificationService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['paymentVerifications'] = $this->service->getAll();

       return view('Account::payments.payment-verifications.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paymentVerifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->store($validate);
        return redirect()->route('paymentVerifications.index')->with('success', 'PaymentVerification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['paymentVerification'] = $this->service->show($id);

        return view("paymentVerifications.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MakePaymentDetail $paymentVerification)
    {
        $data['paymentVerification'] = $paymentVerification;
        //
        return view('Account::payments.payment-verifications.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id,)
    {
        $validate = $request->validate([
            //validate rules
            'verified' => 'required|in:0,1,2,-1',
            'remark' => 'nullable|string|max:255'
        ]);
        $makePaymentDetails = MakePaymentDetail::findOrFail($id);
        $this->service->update($makePaymentDetails, $validate); 

        return redirect()->route('account.payments.payment-verifications.index')->with('success', 'Payment Verification updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MakePaymentDetail $paymentVerification)
    {
        $this->service->delete($paymentVerification);
        return redirect()->route('paymentVerifications.index')->with('success', 'PaymentVerification deleted successfully.');
    }
}
