<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Account\Models\Account;
use Modules\Account\Services\AccountService;
use Illuminate\Http\Request;
use Modules\Account\Models\AccountControl;
use Modules\Account\Models\AccountGroup;
use Modules\Account\Models\AccountSubsidiary;
use Modules\Inventory\Services\ExportService;

class AccountController extends Controller
{

    /**
     * Service variable
     *
     * @var AccountService
     */
    private $service; 
    function __construct(AccountService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['accounts'] = $this->service->getAll();
        $data['accountGroups'] = AccountGroup::all();
        $data['accountControls'] = AccountControl::all();
        $data['accountSubsidiaries'] = AccountSubsidiary::all();
        $data['company_info'] = CompanyInfo::first();

        if ($request->filled('export_type')) {
            $request->merge(['page' =>  '1']);
            $data['accounts'] = $this->service->getAll($data['accounts']->total());
            $filename = 'Chart_of_accounts_list_ ' . today()->format(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'Account::setup.accounts.export.', $filename);
        }
        return view("Account::setup.accounts.index", $data);
    }

    public function getAccountControls($groupId)
    {
        $accountControls = AccountControl::where('account_group_id', $groupId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($accountControls);
    }


    public function getAccountSubsidiaries($controlId)
    {
        $accountSubsidiaries = AccountSubsidiary::where('account_control_id', $controlId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($accountSubsidiaries);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['accountGroups'] = AccountGroup::all();
        $data['accountControls'] = AccountControl::all();
        $data['accountSubsidiaries'] = AccountSubsidiary::all();
        return view('Account::setup.accounts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|numeric',
            'account_group_id' => 'required|exists:account_groups,id',
            'account_control_id' => 'required|exists:account_controls,id',
            'account_subsidiary_id' => 'required|exists:account_subsidiaries,id',
            'opening_balance' => 'nullable|numeric',
            'description' => 'nullable|string|max:255',
        ]);
        $this->service->store($validate);
        return redirect()->route('account.account-setup.accounts.index')->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['account'] = $this->service->show($id);

        return view("Account::setup.accounts.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        $data['account'] = $account;
        $data['accountGroups'] = AccountGroup::all();
        $data['accountControls'] = AccountControl::all();
        $data['accountSubsidiaries'] = AccountSubsidiary::all();
        return view("Account::setup.accounts.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validate = $request->validate([
             'account_group_id' => 'required',
             'account_control_id' => 'required',
             'account_subsidiary_id' => 'required',
             'name' => 'required',
        ]);
        $this->service->update($account, $validate);

        return redirect()->route('account.account-setup.accounts.index')->with('success', 'Account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $this->service->delete($account);
        return redirect()->route('account.account-setup.accounts.index')->with('success', 'Account deleted successfully.');
    }
}
