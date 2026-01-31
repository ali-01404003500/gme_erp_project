<?php

namespace Modules\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Account\Models\Payment ;

class PaymentController extends Controller
{
    


    public function index()
    {
        

        return view('Account::purchase.payments.index');
    }

    public function create()
    {
        

        return view('Account::purchase.payments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        

        return redirect()->route('account.acc_payments.index')->with('success', 'Payment Create Successful');
    }

    public function edit(Payment $payment)
    {
        


        return view('Account::purchase.payments.edit');
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        


        return redirect()->route('account.acc_payments.index')->with('success', 'Payment Update Successful');
    }


    public function destroy($id)
    {
        

        try {
            Payment ::destroy($id);

            return redirect()->route('account.acc_payments.index')->with('success', 'Payment Successfully Deleted!');
        } catch (\Exception $ex) {
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
