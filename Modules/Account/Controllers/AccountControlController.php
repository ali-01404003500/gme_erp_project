<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use Modules\Account\Models\AccountControl;
use Modules\Account\Services\AccountControlService;
use Illuminate\Http\Request;
use Modules\Account\Models\AccountGroup;

class AccountControlController extends Controller
{

    /**
     * Service variable
     *
     * @var AccountControlService
     */
    private $service; 
    function __construct(AccountControlService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['accountControls'] = $this->service->getAll();
        $data['accountGroups'] = AccountGroup::all();

        return view("Account::setup.account-controls.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Account::setup.account-controls.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'account_group_id' => 'required',
            
        ]);
        $this->service->store($validate);
        return redirect()->route('account.account-setup.account-controls.index')->with('success', 'AccountControl created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['accountControl'] = $this->service->show($id);

        return view("Account::setup.account-controls.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountControl $accountControl)
    {
        $data['accountControl'] = $accountControl;
        //
        return view("Account::setup.account-controls.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountControl $accountControl)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($accountControl, $validate);

        return redirect()->route('account.account-setup.account-controls.index')->with('success', 'AccountControl updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountControl $accountControl)
    {
        $this->service->delete($accountControl);
        return redirect()->route('account.account-controls.index')->with('success', 'AccountControl deleted successfully.');
    }
}
