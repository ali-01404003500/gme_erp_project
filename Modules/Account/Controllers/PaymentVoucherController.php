<?php

namespace Modules\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Voucher;
use Modules\Account\Models\VoucherDetail;
use Modules\Account\Services\AccPaymentVoucherService;
use Modules\Account\Services\DataService;

class PaymentVoucherController extends Controller
{

    


    private $dataService;
    private $service;










    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->dataService  = new DataService();

        $this->service      = new AccPaymentVoucherService();
    }










    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        

        if(request()->type == 'description') {
        
            $voucherDetails = VoucherDetail::with('voucher:description,id')->whereHas('transaction', function($q) { 
                $q->whereNull('description');
            })->with('transaction')->get();

            foreach ($voucherDetails as $key => $detail) {
                
                $detail->transaction()->update([
                    'description' => optional($detail->voucher)->description
                ]);
            }

        }
        $vouchers = Voucher::payment()->searchByField('invoice_no')->searchByField('reference')->filterDate()->paginate(30);

        return view('Account::voucher.payments.index', compact('vouchers'));
    }






    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        


        $data               = $this->dataService->getAccountData(['accounts']);
        // $data['companies']  = Company::userCompanies();


        return view('Account::voucher.payments.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE/SAVE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        try {

            DB::transaction(function () use($request) {


                $this->service->validateData($request);



                $this->service->storePaymentVoucher($request);



                $this->service->storePaymentVoucherDetails($request);


                if ($request->draft == 0) {

                    $this->service->approveVoucher();

                    $this->service->makeTransaction();

                }

                // $this->service->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Payment Voucher', date('Y'));

            });


        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('account.voucher-payments.show', $this->service->payment->id)->with('success', 'Payment Voucher Created Successfully!');
    }


    
    public function edit($id)
    {
        $data               = $this->dataService->getAccountData(['accounts']);

        $data['voucher']    = Voucher::payment()->findOrFail($id);
        // $data['companies']  = Company::userCompanies();


        return view('Account::voucher.payments.edit', $data);
    }


    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            // Validate the request data
            $this->service->validateData($request);

    
            // Delegate the update logic to the service
            $this->service->updatePaymentVoucher($request, $id);

           
            return redirect()->route('account.voucher-payments.show', $id)
                             ->with('success', 'Payment Voucher Updated Successfully!');
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }
    }
    







    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        

        $voucher = Voucher::with('details')->find($id);

        return view('Account::voucher.payments.invoice', compact('voucher'));
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function approvePaymentVoucher(Voucher $payment)
    {


        if ($payment->is_approved == 1) {

            return redirect()->back()->withInput()->with('error', 'This Vocuher Already Approved');
        }

        try {

            DB::transaction(function () use($payment) {


                $this->service->payment = $payment;


                $this->service->approveVoucher();


                $this->service->makeTransaction();

            });


        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('account.voucher-payments.show', $this->service->payment->id)->with('success', 'Approved Successfully!');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        

        try {

            DB::transaction(function () use($id) {


                $payment = Voucher::find($id);


                foreach ($payment->details as $key => $detail) {

                    $detail->transactions()->delete();

                    $detail->delete();
                }


                $payment->delete();

            });


            return redirect()->route('account.voucher-payments.index')->with('success', 'Voucher Successfully Deleted!');


        } catch (\Exception $ex) {

            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
