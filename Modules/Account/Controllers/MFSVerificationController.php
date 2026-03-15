<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use Modules\Account\Models\MFSVerification;
use Modules\Account\Services\MFSVerificationService;
use Illuminate\Http\Request;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\CRM\Models\Customer\Customer;

class MFSVerificationController extends Controller
{

    /**
     * Service variable
     *
     * @var MFSVerificationService
     */
    private $service; 
    function __construct(MFSVerificationService $service)
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
    
        $data['bankHeads'] = BankAccount::where('payment_mode','bKash')->select('*','account_name as name')->get();

        return view("Account::mfs-verifications.index", $data);

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mFSVerifications.create');
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
        return redirect()->route('mFSVerifications.index')->with('success', 'MFSVerification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['mFSVerification'] = $this->service->show($id);

        return view("mFSVerifications.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MFSVerification $mFSVerification)
    {
        $data['mFSVerification'] = $mFSVerification;
        //
        return view("mFSVerifications.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MFSVerification $mFSVerification)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($mFSVerification, $validate);

        return redirect()->route('mFSVerifications.index')->with('success', 'MFSVerification updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MFSVerification $mFSVerification)
    {
        $this->service->delete($mFSVerification);
        return redirect()->route('mFSVerifications.index')->with('success', 'MFSVerification deleted successfully.');
    }


    public function updateStatus(Request $request, $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'status'  => 'required',
            'remarks' => 'required|string|max:500',
            'charge'  => 'nullable|numeric|min:0'
        ]);

        $entry = MFSVerification::findOrFail($id);

        $this->service->updateStatus($entry, $validated);


        return back()->with('success', 'MFS Verification marked as ' . ucfirst($validated['status']) . ' successfully.');
 
    }
}
