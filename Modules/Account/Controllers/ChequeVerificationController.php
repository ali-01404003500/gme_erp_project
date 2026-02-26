<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use Modules\Account\Models\ChequeVerification;
use Modules\Account\Services\ChequeVerificationService;
use Illuminate\Http\Request;
use Modules\Account\Models\Account;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\Setup\Bank;
use Modules\CRM\Models\Customer\Customer;

class ChequeVerificationController extends Controller
{

    /**
     * Service variable
     *
     * @var ChequeVerificationService
     */
    private $service; 
    function __construct(ChequeVerificationService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['entries'] = $this->service->getAll();
        $data['banks'] = Bank::select('id','name')->get();
        $data['customers'] = Customer::activeCustomers()->get();
    
        $data['bankHeads'] = BankAccount::where('payment_mode','Online Deposit')->select('*','account_name as name')->get();

        return view("Account::cheque-verifications.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('chequeVerifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'bank_id'     => 'required|exists:banks,id',
            'branch_id'   => 'required|exists:bank_branches,id',
            'cheque_no'   => 'required|string|max:255',
            'cheque_date' => 'nullable|date',
            'amount'      => 'required|numeric|min:0',
            'source_id'   => 'nullable|integer',
            'source_type' => 'nullable|string|max:255',
            'charge'      => 'nullable|numeric|min:0',
        ]);

        $this->service->store($validated);

        return redirect()
            ->back()
            ->with('success', 'Cheque Verification created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['chequeVerification'] = $this->service->show($id);

        return view("chequeVerifications.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChequeVerification $chequeVerification)
    {
        $data['chequeVerification'] = $chequeVerification;
        //
        return view("chequeVerifications.edit", $data);
    }
   public function deposit(Request $request, $id)
    {
        $validated = $request->validate([
            'deposit_date' => 'required|date',
            'head_id'      => 'required|exists:bank_accounts,id',
            'remarks'      => 'nullable|string|max:500',
            'document'     => 'required|array|min:1', 
            'document.*'   => 'required|string',
        ], [
            'document.required'   => 'The attachment field is required.',
            'document.min'        => 'The attachment field is required.',
            'document.*.required' => 'The attachment field is required.',
        ]);

        $validated['head_id'] = BankAccount::find($validated['head_id'])->getAccount()->id;
        $verification = ChequeVerification::findOrFail($id);

        $this->service->deposit($verification, $validated);
        return redirect()->back()->with('success', 'Cheque deposit updated successfully.');
    }


    public function cash(Request $request, $id)
    {

        $verification = ChequeVerification::findOrFail($id);

        $this->service->cash($verification);
        return redirect()->back()->with('success', 'Cheque marked as Cash successfully.');
    }

    public function chequeReturn($id)
    {

        $verification = ChequeVerification::findOrFail($id);

        $this->service->chequeReturn($verification);
        return redirect()->back()->with('success', 'Cheque return to advance cheque list.');
    }
    
    public function updateStatus(Request $request, $id)
    {
        // dd($request->all());
            $validated = $request->validate([
                'status'  => 'required',
                'remarks' => 'required|string|max:500',
                'charge'  => 'nullable|numeric|min:0'
            ]);

            $entry = ChequeVerification::findOrFail($id);

            $this->service->updateStatus($entry, $validated);


        return back()->with('success', 'Cheque marked as ' . ucfirst($validated['status']) . ' successfully.');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChequeVerification $chequeVerification)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($chequeVerification, $validate);

        return redirect()->route('chequeVerifications.index')->with('success', 'ChequeVerification updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChequeVerification $chequeVerification)
    {
        $this->service->delete($chequeVerification);
        return redirect()->route('chequeVerifications.index')->with('success', 'ChequeVerification deleted successfully.');
    }
}
