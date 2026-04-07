<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use Modules\Account\Models\OnlineDepositVerification;
use Modules\Account\Services\OnlineDepositVerificationService;
use Illuminate\Http\Request;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\CRM\Models\Customer\Customer;

class OnlineDepositVerificationController extends Controller
{

    /**
     * Service variable
     *
     * @var OnlineDepositVerificationService
     */
    private $service; 
    function __construct(OnlineDepositVerificationService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        $data['entries'] = $this->service->getAll(); 
        $data['customers'] = Customer::activeCustomers()->get();
    
        $data['bankHeads'] = BankAccount::where('payment_mode','Online Deposit')->select('*','account_name as name')->get();

        return view("Account::online-deposit-verifications.index", $data);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('onlineDepositVerifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->store($validate);
        return redirect()->route('onlineDepositVerifications.index')->with('success', 'OnlineDepositVerification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['onlineDepositVerification'] = $this->service->show($id);

        return view("onlineDepositVerifications.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OnlineDepositVerification $onlineDepositVerification)
    {
        $data['onlineDepositVerification'] = $onlineDepositVerification;
        //
        return view("onlineDepositVerifications.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OnlineDepositVerification $onlineDepositVerification)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($onlineDepositVerification, $validate);

        return redirect()->route('onlineDepositVerifications.index')->with('success', 'OnlineDepositVerification updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OnlineDepositVerification $onlineDepositVerification)
    {
        $this->service->delete($onlineDepositVerification);
        return redirect()->route('onlineDepositVerifications.index')->with('success', 'OnlineDepositVerification deleted successfully.');
    }

   
    public function updateStatus(Request $request, $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'status'  => 'required',
            'remarks' => 'required|string|max:500',
            'charge'  => 'nullable|numeric|min:0'
        ]);

        $entry = OnlineDepositVerification::findOrFail($id);
        dd($entry);
        $this->service->updateStatus($entry, $validated);


        return back()->with('success', 'Online Deposit Verification marked as ' . ucfirst($validated['status']) . ' successfully.');
 
    }

    
    
}
