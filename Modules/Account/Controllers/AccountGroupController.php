<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use Modules\Account\Models\AccountGroup;
use Modules\Account\Services\AccountGroupService;
use Illuminate\Http\Request;

class AccountGroupController extends Controller
{

    /**
     * Service variable
     *
     * @var AccountGroupService
     */
    private $service; 
    function __construct(AccountGroupService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['accountGroups'] = $this->service->getAll();

        return view("Account::setup.account-groups.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Account::setup.account-groups.create');
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
        return redirect()->route('accountGroups.index')->with('success', 'AccountGroup created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['accountGroup'] = $this->service->show($id);

        return view("Account::setup.account-groups.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountGroup $accountGroup)
    {
        $data['accountGroup'] = $accountGroup;
        //
        return view("Account::setup.account-groups.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountGroup $accountGroup)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($accountGroup, $validate);

        return redirect()->route('accountGroups.index')->with('success', 'AccountGroup updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountGroup $accountGroup)
    {
        $this->service->delete($accountGroup);
        return redirect()->route('accountGroups.index')->with('success', 'AccountGroup deleted successfully.');
    }
}
