<?php

namespace Modules\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Voucher;
use Modules\Account\Services\AccReceiveVoucherService;
use Modules\Account\Services\DataService;

class ReceiveVoucherController extends Controller
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

        $this->service      = new AccReceiveVoucherService();
    }










    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        

        $vouchers = Voucher::receive()->searchByField('invoice_no')->searchByField('reference')->filterDate()->paginate(30);

        return view('Account::voucher.receives.index', compact('vouchers'));
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


        return view('Account::voucher.receives.create', $data);
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



                $this->service->storeReceiveVoucher($request);



                $this->service->storeReceiveVoucherDetails($request);


                if ($request->draft == 0) {

                    $this->service->approveVoucher();

                    $this->service->makeTransaction();

                }

                // $this->service->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Receive Voucher', date('Y'));

            });


        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('account.voucher-receives.show', $this->service->receive->id)->with('success', 'Receive Voucher Created Successfully!');
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        

        $voucher = Voucher::with('details')->find($id);

        return view('Account::voucher.receives.invoice', compact('voucher'));
    }





    public function edit($id)
    {
        $data               = $this->dataService->getAccountData(['accounts']);

        $data['voucher']    = Voucher::receive()->findOrFail($id);
        // $data['companies']  = Company::userCompanies();


        return view('Account::voucher.receives.edit', $data);
    }



    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            // Validate the request data
            $this->service->validateData($request);

    
            // Delegate the update logic to the service
            $this->service->updatePaymentVoucher($request, $id);

           
            return redirect()->route('account.voucher-receives.show', $id)
                             ->with('success', 'Receive Voucher Updated Successfully!');
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }
    }
    



    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function approveReceiveVoucher(Voucher $receive)
    {



        if ($receive->is_approved == 1) {

            return redirect()->back()->withInput()->with('error', 'This Vocuher Already Approved');
        }

        try {

            DB::transaction(function () use($receive) {


                $this->service->receive = $receive;


                $this->service->approveVoucher();


                $this->service->makeTransaction();

            });


        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('account.voucher-receives.show', $this->service->receive->id)->with('success', 'Approved Successfully!');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        

        try {

            $receive = Voucher::findOrFail($id);

            DB::transaction(function () use($receive) {

                foreach ($receive->details as $key => $detail) {
                    // dd($detail->transactions);
                    $detail->transactions()->delete();
                    $detail->delete();
                }


                $receive->delete();

            });

            return redirect()->route('account.voucher-receives.index')->with('success', 'Voucher Successfully Deleted!');


        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
}
