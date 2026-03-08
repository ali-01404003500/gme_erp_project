<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use Modules\Account\Models\FundTransfer;
use Modules\Account\Services\FundTransferService;
use Illuminate\Http\Request; 
use Modules\HRMS\Models\Employee; 
use Illuminate\Support\Facades\Log;
use Modules\Account\Models\AccountSetup\BankAccount;


class FundTransferController extends Controller
{

    /**
     * Service variable
     *
     * @var FundTransferService
     */
    private $service; 
    function __construct(FundTransferService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['fundTransfers'] = $this->service->getAll();
        $data['bankAccounts'] = BankAccount::with('bankBranch', 'bank')->where("payment_mode", "Cash")->get(); 
        return view("Account::fund-transfers.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Account::fund-transfers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
  
            //validate rules
            'transfer_date' => 'required|date',
            'transfer_type' => 'required|in:bank_to_bank,bank_to_cash,cash_to_bank,bkash_to_bank',
            'transfer_from' => 'required|string',
            'transfer_to' => 'required|string',
            'cheque_date' => 'nullable|date',
            'cheque_no' => 'nullable|string',
            'amount' => 'required|string',
            'charge' => 'nullable|string',
            'remarks' => 'required|string',
            'attachments' => 'nullable|string',
            'status' => 'required|string',
        ]);
        $result = $this->service->store($validate);
        return redirect()->route('account.fund-transfers.edit',$result->id)->with('success', 'FundTransfer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['fundTransfer'] = $this->service->show($id);

        return view("Account::fund-transfers.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FundTransfer $fundTransfer)
    {
        $data['fundTransfer'] = $fundTransfer;
        //
        return view("Account::fund-transfers.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FundTransfer $fundTransfer)
    {
        $validate = $request->validate([
            //validate rules
            'transfer_date' => 'required|date',
            'transfer_type' => 'required|in:bank_to_bank,bank_to_cash,cash_to_bank,bkash_to_bank',
            'transfer_from' => 'required|string',
            'transfer_to' => 'required|string',
            'cheque_date' => 'nullable|date',
            'cheque_no' => 'nullable|string',
            'amount' => 'required|string',
            'charge' => 'nullable|string',
            'remarks' => 'required|string',
            'attachments' => 'nullable|string',
            'status' => 'required|string',
        ]);
        $this->service->update($fundTransfer, $validate);

        return redirect()->route('account.fund-transfers.index')->with('success', 'FundTransfer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FundTransfer $fundTransfer)
    {
        $this->service->delete($fundTransfer);
        return redirect()->route('account.fund-transfers.index')->with('success', 'FundTransfer deleted successfully.');
    }


    public function getAccount(Request $request){
        
        $data = [];
        switch($request->transfer_type)
        {
            case 'bank_to_bank':
                $data['sender_accounts'] = BankAccount::where('payment_mode','Online Deposit')->get();
                $data['receiver_accounts'] = BankAccount::where('payment_mode','Online Deposit')->get();
            break;
            case 'bank_to_cash':
                $data['sender_accounts'] = BankAccount::where('payment_mode','Online Deposit')->get();
                $data['receiver_accounts'] = BankAccount::where('payment_mode','Cash')->get();
            break;
            case 'cash_to_bank':
                $data['sender_accounts'] = BankAccount::where('payment_mode','Cash')->get();
                $data['receiver_accounts'] = BankAccount::where('payment_mode','Online Deposit')->get();
            break;
            case 'bkash_to_bank':
                $data['sender_accounts'] = BankAccount::where('payment_mode','bKash')->get();
                $data['receiver_accounts'] = BankAccount::where('payment_mode','Online Deposit')->get();
            break;

        } 
        return response()->json($data);
    }
 
}
