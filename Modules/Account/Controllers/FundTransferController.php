<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use Modules\Account\Models\FundTransfer;
use Modules\Account\Services\FundTransferService;
use Illuminate\Http\Request;

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
        ]);
        $this->service->store($validate);
        return redirect()->route('account.fund-transfers.index')->with('success', 'FundTransfer created successfully.');
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
 
}
