<?php

namespace Modules\Account\Controllers\AccountSetup;

use App\Http\Controllers\Controller;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Services\AccountSetup\BankAccountService;
use Illuminate\Http\Request;
use Modules\Account\Models\Bank;
use Modules\Account\Models\Setup\BankBranch;

class BankAccountController extends Controller
{

    /**
     * Service variable
     *
     * @var BankAccountService
     */
    private $service; 
    function __construct(BankAccountService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['bankAccounts'] = $this->service->getAll();
        $data['banks'] = Bank::all();
        $data['branches'] = BankBranch::all();

        return view("Account::setup.bank-accounts.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Account::setup.bank-accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validate =  $request->validate([
            'payment_mode' => 'required|string|min:1|max:255|in:Cash,Online Deposit,Card Payment,Bank,bKash,Nagad,Rocket',
            'account_name' => 'required|string|min:1|max:255',
            'account_code' => 'required|string|min:1|max:255|unique:bank_accounts,account_code',
            'opening_balance' => 'required|integer|min:0',
            'bank_id' => 'required_if:payment_mode,Online Deposit,Card Payment,Bank|nullable|exists:banks,id',
            'bank_branch_id' => 'required_if:payment_mode,Online Deposit,Card Payment,Bank|nullable|exists:bank_branches,id',
            'bank_account_no' => 'required_if:payment_mode,Online Deposit,Card Payment,Bank|nullable|string|min:1|max:255',
        ]);

        $this->service->store($validate);
       
        return redirect()->route('account.account-setup.bank-accounts.index')->with('success', 'BankAccount created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['bankAccount'] = $this->service->show($id);

        return view("Account::setup.bank-accounts.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankAccount $bankAccount)
    {
        $data['bankAccount'] = $bankAccount;
        return view("Account::setup.bank-accounts.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $validate = $request->validate([
            'payment_mode' => 'required|string|min:1|max:255|in:Cash,Online Deposit,Card Payment,Bank,bKash,Nagad,Rocket',
            'account_name' => 'required|string|min:1|max:255',
            'account_code' => 'required|string|min:1|max:255|unique:bank_accounts,account_code,' . $bankAccount->id,
            'opening_balance' => 'required|integer|min:0',
            'bank_id' => 'required_if:payment_mode,Online Deposit,Card Payment,Bank|nullable|exists:banks,id',
            'bank_branch_id' => 'required_if:payment_mode,Online Deposit,Card Payment,Bank|nullable|exists:bank_branches,id',
            'bank_account_no' => 'required_if:payment_mode,Online Deposit,Card Payment,Bank|nullable|string|min:1|max:255',
        ]);
        $this->service->update($bankAccount, $validate);

        return redirect()->route('account.account-setup.bank-accounts.index')->with('success', 'BankAccount updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        $this->service->delete($bankAccount);
        return redirect()->route('account.account-setup.bank-accounts.index')->with('success', 'BankAccount deleted successfully.');
    }


    
    public function getAccounts(Request $request)
    {
        $request->validate([
            'payment_mode' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card Payment,EMI'
        ]);
        
        $bankAccounts = BankAccount::query()
            ->where('payment_mode', $request->payment_mode)
            ->get();

        return response()->json($bankAccounts);
    }
}
