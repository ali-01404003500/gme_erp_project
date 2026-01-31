<?php

namespace Modules\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Voucher;
use Modules\Account\Services\AccJournalVoucherService;
use Modules\Account\Services\DataService;

class JournalVoucherController extends Controller
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

        $this->service      = new AccJournalVoucherService();
    }










    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        

        $vouchers = Voucher::journal()->searchByField('invoice_no')->searchByField('reference')->filterDate()->paginate(30);

        return view('Account::voucher.journals.index', compact('vouchers'));
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


        return view('Account::voucher.journals.create', $data);
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



                $this->service->storeJournalVoucher($request);



                $this->service->storeJournalVoucherDetails($request);


                if ($request->draft == 0) {

                    $this->service->approveVoucher();

                    $this->service->makeTransaction();
                }

                // $this->service->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Journal Voucher', date('Y'));
            });

        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('account.voucher-journals.show', $this->service->journal->id)->with('success', 'Journal Voucher Created Successfully!');
    }



 
    public function edit($id)
    {
        $data               = $this->dataService->getAccountData(['accounts']);

        $data['voucher']    = Voucher::journal()->findOrFail($id);
        // $data['companies']  = Company::userCompanies();


        return view('Account::voucher.journals.edit', $data);
    }


    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            // Validate the request data
            $this->service->validateData($request);

    
            // Delegate the update logic to the service
            $this->service->updateJournalVoucher($request, $id);

           
            return redirect()->route('account.voucher-journals.show', $id)
                             ->with('success', 'Journal Voucher Updated Successfully!');
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

        return view('Account::voucher.journals.invoice', compact('voucher'));
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function approveJournalVoucher(Voucher $journal)
    {



        if ($journal->is_approved == 1) {

            return redirect()->back()->withInput()->with('error', 'This Vocuher Already Approved');
        }

        try {

            DB::transaction(function () use ($journal) {


                $this->service->journal = $journal;


                $this->service->approveVoucher();


                $this->service->makeTransaction();
            });

        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('account.voucher-journals.show', $this->service->journal->id)->with('success', 'Approved Successfully!');
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


                $journal = Voucher::find($id);


                foreach ($journal->details as $key => $detail) {

                    $detail->transactions()->delete();

                    $detail->delete();
                }


                $journal->delete();
            });


            return redirect()->route('account.voucher-journals.index')->with('success', 'Voucher Successfully Deleted!');
            
        } catch (\Exception $ex) {

            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
