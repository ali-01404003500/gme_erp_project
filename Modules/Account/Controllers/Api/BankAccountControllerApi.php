<?php

namespace Modules\Account\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Services\AccountSetup\BankAccountService;
use Illuminate\Http\Request;
use Modules\Account\Models\Bank;
use Modules\Account\Models\Setup\BankBranch;

class BankAccountControllerApi extends Controller
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
        $bankAccounts = $this->service->getAll();
        $banks = Bank::all();
        $branches = BankBranch::all();

        return response()->json([
            'bankAccounts' => $bankAccounts,
            'banks' => $banks,
            'branches' => $branches,
        ]);
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

        $bankAccount = $this->service->store($validate);
       
        return response()->json([
            'message' => 'BankAccount created successfully.',
            'bankAccount' => $bankAccount,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $bankAccount = $this->service->show($id);

        return response()->json([
            'bankAccount' => $bankAccount,
        ]);
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

        return response()->json([
            'message' => 'BankAccount updated successfully.',
            'bankAccount' => $bankAccount,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        $this->service->delete($bankAccount);
        return response()->json([
            'message' => 'BankAccount deleted successfully.',
        ]);
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