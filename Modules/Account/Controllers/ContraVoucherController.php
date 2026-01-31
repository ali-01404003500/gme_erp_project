<?php

namespace Modules\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Voucher;
use Modules\Account\Services\AccContraVoucherService;
use Modules\Account\Services\DataService;

class ContraVoucherController extends Controller
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

        $this->service      = new AccContraVoucherService();
    }










    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        

        $vouchers = Voucher::contra()->searchByField('invoice_no')->searchByField('reference')->filterDate()->paginate(30);

        return view('Account::voucher.contras.index', compact('vouchers'));
    }






    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        

        $data               = $this->dataService->getAccountData(['accounts']);
        // $data['company']    = Company::userCompanies();


        return view('Account::voucher.contras.create', $data);
    }














    /*
     |--------------------------------------------------------------------------
     | STORE/SAVE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        try {

            DB::transaction(function () use ($request) {


                $this->service->validateData($request);



                $this->service->storeContraVoucher($request);



                $this->service->storeContraVoucherDetails($request);


                if ($request->draft == 0) {

                    $this->service->approveVoucher();

                    $this->service->makeTransaction();
                }

                // $this->service->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Contra Voucher', date('Y'));
            });
        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('account.voucher-contras.show', $this->service->contra->id)->with('success', 'Contra Voucher Created Successfully!');
    }



 
    public function edit($id)
    {
        $data               = $this->dataService->getAccountData(['accounts']);

        $data['voucher']    = Voucher::contra()->findOrFail($id);
        // $data['companies']  = Company::userCompanies();


        return view('Account::voucher.contras.edit', $data);
    }


    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            // Validate the request data
            $this->service->validateData($request);

    
            // Delegate the update logic to the service
            $this->service->updateContraVoucher($request, $id);

           
            return redirect()->route('account.voucher-contras.show', $id)
                             ->with('success', 'Contra Voucher Updated Successfully!');
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

        return view('Account::voucher.contras.invoice', compact('voucher'));
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function approveContraVoucher(Voucher $contra)
    {



        if ($contra->is_approved == 1) {

            return redirect()->back()->withInput()->with('error', 'This Vocuher Already Approved');
        }

        try {

            DB::transaction(function () use ($contra) {


                $this->service->contra = $contra;


                $this->service->approveVoucher();


                $this->service->makeTransaction();
            });
        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('account.voucher-contras.show', $this->service->contra->id)->with('success', 'Approved Successfully!');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        

        try {

            DB::transaction(function () use ($id) {


                $contra = Voucher::find($id);


                foreach ($contra->details as $key => $detail) {

                    $detail->transactions()->delete();

                    $detail->delete();
                }


                $contra->delete();
            });


            return redirect()->route('account.voucher-contras.index')->with('success', 'Voucher Successfully Deleted!');
        } catch (\Exception $ex) {

            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
