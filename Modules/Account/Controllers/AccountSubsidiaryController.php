<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use Modules\Account\Models\AccountSubsidiary;
use Modules\Account\Services\AccountSubsidiaryService;
use Illuminate\Http\Request;
use Modules\Account\Models\AccountControl;
use Modules\Account\Models\AccountGroup;

class AccountSubsidiaryController extends Controller
{

    /**
     * Service variable
     *
     * @var AccountSubsidiaryService
     */
    private $service; 
    function __construct(AccountSubsidiaryService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['accountSubsidiaries'] = $this->service->getAll();

        $data['accountGroups'] = AccountGroup::all();

        $data['accountControls'] = AccountControl::all();

        return view("Account::setup.account-subsidiaries.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Account::setup.account-subsidiaries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'account_group_id' => 'required',
            'account_control_id' => 'required',
            'name'=>'required',
        ]);
        $this->service->store($validate);
        return redirect()->route('account.account-setup.account-subsidiaries.index')->with('success', 'AccountSubsidiary created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['accountSubsidiary'] = $this->service->show($id);

        return view("Account::setup.account-subsidiaries.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountSubsidiary $accountSubsidiary)
    {
        $data['accountSubsidiary'] = $accountSubsidiary;
        //
        return view("Account::setup.account-subsidiaries.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountSubsidiary $accountSubsidiary)
    {
        $validate = $request->validate([
            //validate rules
            'account_group_id' => 'required',
            'account_control_id' => 'required',
            'name'=>'required',
        ]);
        $this->service->update($accountSubsidiary, $validate);

        return redirect()->route('account.account-setup.account-subsidiaries.index')->with('success', 'AccountSubsidiary updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountSubsidiary $accountSubsidiary)
    {
        $this->service->delete($accountSubsidiary);
        return redirect()->route('account.account-setup.account-subsidiaries.index')->with('success', 'AccountSubsidiary deleted successfully.');
    }
}
