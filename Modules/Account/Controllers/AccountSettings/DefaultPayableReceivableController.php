<?php

namespace Modules\Account\Controllers\AccountSettings;

use App\Http\Controllers\Controller;
use Modules\Account\Models\AccountSettings\DefaultPayableReceivable;
use Modules\Account\Services\AccountSettings\DefaultPayableReceivableService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Account\Models\Account;

class DefaultPayableReceivableController extends Controller
{

    /**
     * Service variable
     *
     * @var DefaultPayableReceivableService
     */
    private $service; 
    function __construct(DefaultPayableReceivableService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['defaultPayableReceivables'] = $this->service->getAll();

        return view("Account::account-settings.default-payable-receivables.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['accounts'] = Account::all();
        $data['defaultPayableReceivables'] = DefaultPayableReceivable::all();
        return view('Account::account-settings.default-payable-receivables.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $types = [
            'default_payment_account',
            'default_bill_expense_payable',
            'default_claim_bill',
            'default_invoice_income_receivable',
            'default_advance_salary',
            'default_employee_advance',
            'default_salary_payable',
            'default_owner_equity',
            'default_bank_charge',
            'default_tax_payable',
            'default_vendor_advance',
            'default_customer_advance_payment',
        ];
    
        $validatedData = $request->validate([
            'type' => ['required', 'array'],
            'type.*' => ['required', 'string', Rule::in($types)],
            'account_id' => ['required', 'array'],
            'account_id.*' => ['nullable', 'integer', 'exists:accounts,id'],
        ]);
    
        // Combine `type` and `account_id` into a single structure
        $data = [];
        foreach ($validatedData['type'] as $type) {
            $data[] = [
                'type' => $type,
                'account_id' => $validatedData['account_id'][$type] ?? null,
            ];
        }
    
        // Pass the prepared data to the service
        $this->service->store($data);
    
        return redirect()
            ->route('account.account-settings.default-payable-receivables.create')
            ->with('success', 'Default Payable/Receivable created successfully.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['defaultPayableReceivable'] = $this->service->show($id);

        return view("defaultPayableReceivables.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DefaultPayableReceivable $defaultPayableReceivable)
    {
        $data['defaultPayableReceivable'] = $defaultPayableReceivable;
        //
        return view("defaultPayableReceivables.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DefaultPayableReceivable $defaultPayableReceivable)
    {
        $validate = $request->validate([
            'default_payment_account' => 'nullable|string|max:255',
            'default_bill_expense_payable' => 'nullable|string|max:255',
            'default_claim_bill' => 'nullable|string|max:255',
            'default_invoice_income_receivable' => 'nullable|string|max:255',
            'default_advance_salary' => 'nullable|string|max:255',
            'default_employee_advance' => 'nullable|string|max:255',
            'default_salary_payable' => 'nullable|string|max:255',
            'default_owner_equity' => 'nullable|string|max:255',
            'default_bank_charge' => 'nullable|string|max:255',
            'default_tax_payable' => 'nullable|string|max:255',
            'default_vendor_advance' => 'nullable|string|max:255',
            'default_customer_advance_payment' => 'nullable|string|max:255',
        ]);
        $this->service->update($defaultPayableReceivable, $validate);

        return redirect()->route('account.account-settings.default-payable-receivables.index')->with('success', 'DefaultPayableReceivable updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DefaultPayableReceivable $defaultPayableReceivable)
    {
        $this->service->delete($defaultPayableReceivable);
        return redirect()->route('defaultPayableReceivables.index')->with('success', 'DefaultPayableReceivable deleted successfully.');
    }
}
