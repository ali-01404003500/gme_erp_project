<?php

namespace Modules\Account\Controllers;

use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\Supplier;
use Modules\Account\Services\AccountTransactionService;

class SupplierController extends Controller
{
    


    private $transactionService;






    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->transactionService   = new AccountTransactionService();
    }



    public function index()
    {
        

        $suppliers = Supplier::query()->paginate(30);

        return view('Account::party.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        

        return view('Account::party.suppliers.create');
    }

    public function store(Request $request)
    {
        


        $request->validate([
            'name'   => 'required',
        ]);

        // try {


            DB::transaction(function () use($request) {
                

                $account = Account::create([

                    'name'                  => $request->name,
                    'account_group_id'      => 2,
                    'account_control_id'    => 3,
                    'account_subsidiary_id' => 4,
                    'opening_balance'       => $request->opening_balance ?? 0,
                    'balance_type'          => 'Credit'
                ]);


                $supplier = Supplier::create([

                    'account_id'        => $account->id,
                    'name'              => $request->name,
                    'mobile'            => $request->mobile,
                    'email'             => $request->email,
                    'address'           => $request->address,
                    'opening_balance'   => $request->opening_balance ?? 0
                ]);


                // $openingAccount = $this->transactionService->getPartyOpeningAccount();



                // $this->transactionService->storeTransaction($supplier,    'inv-3000' . $supplier->id,    $openingAccount,    $supplier->opening_balance, 0,  date('Y-m-d'), 'debit', 'Suppliier Opening Debit', $description = 'Supplier Opening Balance');
                // $this->transactionService->storeTransaction($supplier,    'inv-3000' . $supplier->id,    $account,           0, $supplier->opening_balance,  date('Y-m-d'), 'credit', 'Supplier Opening Credit', $description = 'Supplier Opening Balance');



            });

            return redirect()->route('account.acc-suppliers.index')->with('success', 'Supplier Create Successful');

        // } catch (Exception $e) {
            
        //     return redirect()->back()->with('error', $e);
        // }
    }

    public function edit($id)
    {
        

        $supplier = Supplier::query()->find($id);

        return view('Account::party.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        

        try {

            DB::transaction(function () use($request, $id) {
                    

                $supplier = Supplier::query()->find($id);
                
                
                $supplier->update($request->except('_token', '_method'));

                $supplier->account()->update([

                    'name' => $request->name,
                ]);



                // $openingAccount = $this->transactionService->getPartyOpeningAccount();



                // $this->transactionService->storeTransaction($supplier,    'inv-3000' . $supplier->id,    $openingAccount,     $supplier->opening_balance, 0,  date('Y-m-d'), 'debit', 'Suppliier Opening Debit', $description = 'Supplier Opening Balance');
                // $this->transactionService->storeTransaction($supplier,    'inv-3000' . $supplier->id,    $supplier->account,  0, $supplier->opening_balance,  date('Y-m-d'), 'credit', 'Supplier Opening Credit', $description = 'Supplier Opening Balance');

            });


            return redirect()->route('account.acc-suppliers.index')->with('success', 'Supplier Update Successful');
            
        } catch (Exception $e) {


            return redirect()->back()->with('error', $e);
        }
    }


    public function destroy($id)
    {

        

        try {

            DB::transaction(function () use($id) {
                    
                
                $supplier = Supplier::find($id);


                Account::destroy($supplier->account_id);


                Supplier::destroy($id);

            });

            return redirect()->route('account.acc-suppliers.index')->with('success', 'Supplier Successfully Deleted!');

        } catch (\Exception $ex) {
            
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
