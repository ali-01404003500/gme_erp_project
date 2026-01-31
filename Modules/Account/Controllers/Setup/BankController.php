<?php

namespace Modules\Account\Controllers\Setup;

use App\Http\Controllers\Controller;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Services\Setup\BankService;
use Illuminate\Http\Request;

class BankController extends Controller
{

    /**
     * Service variable
     *
     * @var BankService
     */
    private $service; 
    function __construct(BankService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['banks'] = $this->service->getAll();

        return view("Account::setup.banks.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     * Modules\Account\resources\views\setup\banks
     */
    public function create()
    {
        return view('Account::setup.banks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
            'name'=> 'required|unique:banks,name,NULL,id,deleted_at,NULL',
        ]);
        $this->service->store($validate);
        return redirect()->route('account.account-setup.banks.index')->with('success', 'Bank created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['bank'] = $this->service->show($id);

        return view("Account::setup.banks.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        $data['bank'] = $bank;
        //
        return view("Account::setup.banks.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bank $bank)
    {
        $validate = $request->validate([
            //validate rules
            'name'=> 'required|unique:banks,name,'.$bank->id,
        ]);
        $this->service->update($bank, $validate);

        return redirect()->route('account.account-setup.banks.index')->with('success', 'Bank updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        $this->service->delete($bank);
        return redirect()->route('account.account-setup.banks.index')->with('success', 'Bank deleted successfully.');
    }
}
