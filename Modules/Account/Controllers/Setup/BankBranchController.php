<?php

namespace Modules\Account\Controllers\Setup;

use App\Http\Controllers\Controller;
use Modules\Account\Models\Setup\BankBranch;
use Modules\Account\Services\Setup\BankBranchService;
use Illuminate\Http\Request;
use Modules\Account\Models\Bank;

class BankBranchController extends Controller
{

    /**
     * Service variable
     *
     * @var BankBranchService
     */
    private $service; 
    function __construct(BankBranchService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['bankBranches'] = $this->service->getAll();

        $data['banks'] = Bank::all();

        return view("Account::setup.bank-branches.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Account::setup.bank-branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
            'name'=> 'required|unique:bank_branches,name,NULL,id,bank_id,'.$request->bank_id.',deleted_at,NULL',
            'bank_id'=> 'required|exists:banks,id',
        ]);
        $this->service->store($validate);
        return redirect()->route('account.account-setup.bank-branches.index')->with('success', 'Bank Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['bankBranch'] = $this->service->show($id);

        return view("Account::setup.bank-branches.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankBranch $bankBranch)
    {
        $data['bankBranch'] = $bankBranch;
        //
        return view("Account::setup.bank-branches.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankBranch $bankBranch)
    {
        $validate = $request->validate([
            //validate rules
            'name'=> 'required',
            'bank_id'=> 'required|exists:banks,id',
        ]);
        $this->service->update($bankBranch, $validate);

        return redirect()->route('account.account-setup.bank-branches.index')->with('success', 'Bank Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankBranch $bankBranch)
    {
        $this->service->delete($bankBranch);
        return redirect()->route('account.account-setup.bank-branches.index')->with('success', 'Bank Branch deleted successfully.');
    }

    public function getBranches(Request $request)
    {
        $branches = BankBranch::where('bank_id', $request->bank_id)->get();
        return response()->json($branches);
    }
}
