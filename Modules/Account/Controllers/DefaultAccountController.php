<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use Modules\Account\Models\DefaultAccount;
use Modules\Account\Services\DefaultAccountService;
use Illuminate\Http\Request;

class DefaultAccountController extends Controller
{

    /**
     * Service variable
     *
     * @var DefaultAccountService
     */
    private $service; 
    function __construct(DefaultAccountService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['defaultAccounts'] = $this->service->getAll();

        return view("defaultAccounts.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('defaultAccounts.create');
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
        return redirect()->route('defaultAccounts.index')->with('success', 'DefaultAccount created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['defaultAccount'] = $this->service->show($id);

        return view("defaultAccounts.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DefaultAccount $defaultAccount)
    {
        $data['defaultAccount'] = $defaultAccount;
        //
        return view("defaultAccounts.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DefaultAccount $defaultAccount)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($defaultAccount, $validate);

        return redirect()->route('defaultAccounts.index')->with('success', 'DefaultAccount updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DefaultAccount $defaultAccount)
    {
        $this->service->delete($defaultAccount);
        return redirect()->route('defaultAccounts.index')->with('success', 'DefaultAccount deleted successfully.');
    }
}
