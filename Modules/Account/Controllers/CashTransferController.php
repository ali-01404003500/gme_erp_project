<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use Modules\Account\Models\CashTransfer;
use Modules\Account\Services\CashTransferService;
use Illuminate\Http\Request;

class CashTransferController extends Controller
{

    /**
     * Service variable
     *
     * @var CashTransferService
     */
    private $service;
    function __construct(CashTransferService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['cashTransfers'] = $this->service->getAll();
        $data['currentEmployee'] = \Modules\HRMS\Models\Employee::where('user_id', auth()->id())->first();

        return view("Account::cash-transfers.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['employees'] = \Modules\HRMS\Models\Employee::all();
        $data['currentEmployee'] = \Modules\HRMS\Models\Employee::where('user_id', auth()->id())->first();
        return view('Account::cash-transfers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'from_employee_id' => 'required|exists:employees,id',
            'to_employee_id' => 'required|exists:employees,id|different:from_employee_id',
            'amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);
        try {
            $this->service->store($validate);
            return redirect()->route('account.cash-transfers.index')->with('success', 'CashTransfer created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['cashTransfer'] = $this->service->show($id);
        return view("Account::cash-transfers.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashTransfer $cashTransfer)
    {
        $data['cashTransfer'] = $cashTransfer;
        $data['employees'] = \Modules\HRMS\Models\Employee::all();
        return view("Account::cash-transfers.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashTransfer $cashTransfer)
    {
        $validate = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'required|date',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        try {
            $this->service->update($cashTransfer, $validate);
            return redirect()->route('account.cash-transfers.index')->with('success', 'CashTransfer updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function confirm(Request $request, $id)
    {
        /** @var CashTransfer $cashTransfer */
        $cashTransfer = $this->service->show($id);
        $validate = $request->validate([
            'received_amount' => 'required|numeric',
            'is_cash_count_matched' => 'required',
        ]);

        try {
            $this->service->confirm($cashTransfer, $validate);
            return redirect()->route('account.cash-transfers.index')->with('success', 'CashTransfer confirmation processed.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashTransfer $cashTransfer)
    {
        $this->service->delete($cashTransfer);
        return redirect()->route('account.cash-transfers.index')->with('success', 'CashTransfer deleted successfully.');
    }
}
