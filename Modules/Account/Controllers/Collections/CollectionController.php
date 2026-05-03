<?php

namespace Modules\Account\Controllers\Collections;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Services\AutocompleteService;
use Modules\Account\Models\Collections\Collection;
use Modules\Account\Services\Collections\CollectionService;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Broker;
use Modules\CRM\Models\Customer\Customer;
use Modules\HRMS\Models\Employee;
use Modules\Inventory\Services\ExportService;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Models\Vendor;

class CollectionController extends Controller
{

    /**
     * Service variable
     *
     * @var CollectionService
     */
    private $service;
    function __construct(CollectionService $service)
    {
        $this->service = $service;
        $this->middleware('permited')->except(['customerAutocomplete','getBallance']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['collections'] = $this->service->getAll();
        $data['customers'] = Customer::select('id', 'company_name as name')->get();

        return view("Account::collections.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['customers'] = Customer::select('*', 'company_name as name')->get();
        return view("Account::collections.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'voucher_type' => 'required|string|in:Collection',
            'collection_type' => 'required|string|in:customer',
            'collection_from' => 'required|integer|exists:customers,id',
            'payments_total_amount' => 'required|numeric|min:0',
            'payments_payable_amount' => 'required|numeric|min:0',
            'payments_due_amount' => 'required|numeric|min:0',
            'payments_advance_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,verified,approved,denied',
        ]);

        $payments = $request->validate([
            'payments_pay_mode' => 'nullable|array',
            'payments_pay_mode.*' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment,AIT,Waiver,Waiver Bad Debt',
            'payments_bank_id' => 'nullable|array',
            'payments_bank_id.*' => 'nullable|integer',
            'payments_branch_id' => 'nullable|array',
            'payments_branch_id.*' => 'nullable|integer|exists:bank_branches,id',
            'payments_emi_id' => 'nullable|array',
            'payments_emi_id.*' => 'nullable|integer|exists:e_m_i_entries,id',
            'payments_transaction_id' => 'nullable|array',
            'payments_transaction_id.*' => 'nullable|string',
            'payments_date' => 'nullable|array',
            'payments_date.*' => 'required|date',
            'payments_amount' => 'nullable|array',
            'payments_amount.*' => 'nullable|numeric|min:0',
            'payments_attachments' => 'nullable|array',
            'payments_attachments.*' => 'nullable|string',
            'payments_verified' => 'nullable|array',
            'payments_verified.*' => 'nullable|in:0,1',
            'payments_remark' => 'nullable|array',
            'payments_remark.*' => 'nullable|string',
            'payments_total_amount' => 'required|numeric',
            'payments_payable_amount' => 'required|numeric',
            'payments_due_amount' => 'nullable|numeric',
            'payments_advance_amount' => 'nullable|numeric'
        ]);
        $this->service->store($validate, $payments);
        return redirect()->route('account.collections.collections.index')->with('success', 'Collection created successfully.');
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $data['collection'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first(); // Adjust based on your actual model

        // dd($data['collection']);
        // Check if export is requested
        if ($request->filled('export_type')) {
            $filename = 'Collection_Receipt_' . $data['collection']->collection_id . '_' . today()->format('Y_m_d');

            return (new ExportService())->exportData(
                $data,
                'Account::collections.export.',
                $filename
            );
        }

        return view("Account::collections.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        $data['collection'] = $collection;
        // dd($data['collection']->payments->bank);
        $data['customers'] = Customer::select('id', 'company_name as name')->get();
        //
        return view("Account::collections.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        $validate = $request->validate([
            'voucher_type' => 'required|string|in:Collection',
            'collection_type' => 'required|string|in:customer',
            'collection_from' => 'required|integer|exists:customers,id',
            'payments_total_amount' => 'required|numeric|min:0',
            'payments_payable_amount' => 'required|numeric|min:0',
            'payments_due_amount' => 'required|numeric|min:0',
            'payments_advance_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,verified,approved,denied',
        ]);

        $payments = $request->validate([
            'payments_id' => 'array',
            'payments_id.*' => 'nullable|integer|exists:payments,id',
            'payments_pay_mode' => 'nullable|array',
            'payments_pay_mode.*' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment,AIT,Waiver,Waiver Bad Debt',
            'payments_bank_id' => 'nullable|array',
            'payments_bank_id.*' => 'nullable|integer',
            'payments_branch_id' => 'nullable|array',
            'payments_branch_id.*' => 'nullable|integer|exists:bank_branches,id',
            'payments_emi_id' => 'nullable|array',
            'payments_emi_id.*' => 'nullable|integer|exists:e_m_i_entries,id',
            'payments_transaction_id' => 'nullable|array',
            'payments_transaction_id.*' => 'nullable|string',
            'payments_date' => 'nullable|array',
            'payments_date.*' => 'required|date',
            'payments_amount' => 'nullable|array',
            'payments_amount.*' => 'nullable|numeric|min:0',
            'payments_attachments' => 'nullable|array',
            'payments_attachments.*' => 'nullable|string',
            'payments_verified' => 'nullable|array',
            'payments_verified.*' => 'nullable|in:0,1',
            'payments_remark' => 'nullable|array',
            'payments_remark.*' => 'nullable|string',
            'payments_total_amount' => 'required|numeric',
            'payments_payable_amount' => 'required|numeric',
            'payments_due_amount' => 'nullable|numeric',
            'payments_advance_amount' => 'nullable|numeric'
        ]);
        $this->service->update($collection, $validate, $payments);

        // Determine success message based on status
        $status = $validate['status'];
        if ($status === 'verified') {
            $message = 'Payment Requisition Verified Successfully (Verified).';
        } elseif ($status === 'approved') {
            $message = 'Payment Requisition Approved Successfully (Final).';
        } elseif ($status === 'denied') {
            $message = 'Payment Requisition Denied.';
        } else {
            $message = 'Collection updated successfully.';
        }

        return redirect()->route('account.collections.collections.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        $this->service->delete($collection);
        return redirect()->route('account.collections.collections.index')->with('success', 'Collection deleted successfully.');
    }
    public function getAccountsByType(Request $request)
    {
        $type = $request->query('type');
        $query = null;

        switch ($type) {
            case 'customer':
                // Assuming Customer model and 'company_name' field
                $query = Customer::select('id', 'company_name as name');
                break;
            case 'vendor':
                // Assuming Vendor model from Purchase module and 'name' field
                $query = Vendor::select('id', 'company_name as name');
                break;
            case 'supplier':
                // Assuming Supplier model from Purchase module and 'company_name' field
                $query = Supplier::select('id', 'company_name as name');
                break;
            case 'broker':
                // Assuming Broker model
                $query = Broker::select('id', 'broker_name as name');
                break;
            case 'employee':
                // Adjust the model and field name as per your application structure
                $query = Employee::select('id', 'full_name as name');
                break;
        }

        $data = $query ? $query->get() : [];

        return response()->json($data);
    }


    public function getBallance(Request $request)
    {
        $validate = $request->validate([
            'type' => 'required',
            'account_id' => 'required',
        ]);

        $type = $request->query('type');
        $query = null;
        switch ($type) {
            case 'customer':
                // Assuming Customer model and 'company_name' field
                $query = Customer::find($request->input('account_id'))->getAccount();
                break;
            case 'vendor':
                // Assuming Vendor model from Purchase module and 'name' field
                $query = Vendor::find($request->query('account_id'))->getAccount();
                break;
            case 'supplier':
                // Assuming Supplier model from Purchase module and 'company_name' field
                $query = Supplier::find($request->query('account_id'))->getAccount();
                break;
            case 'broker':
                // Assuming Broker model from CRM module and 'broker_name' field
                $query = Broker::find($request->query('account_id'))->getAccount();
                break;
            case 'employee':
                // Adjust the model and field name as per your application structure
                $query = Employee::find($request->query('account_id'))->getAccount();
                break;
        }

        $data = $query;
        $data['balance'] = $data;
        return response()->json($data);
    }

    public function customerAutocomplete(Request $request, AutocompleteService $autocompleteService)
    { 
        //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
  
        $type = $request->type;
        $data = '';

        switch ($type) {
            case 'customer':
                $data = $autocompleteService->customerSearch(
                    Customer::class,
                    ['company_name','address','phone'],
                    $request->search,
                    ['id', 'company_name','company_place_id', 'phone', 'customer_type', 'address'],
                    30
                ); 
                break;
            case 'vendor': 
                $data = $autocompleteService->search(
                    Vendor::class,
                    ['company_name'],
                    $request->search,
                    ['id', 'company_name'],
                    30
                );  
                break;
            case 'supplier': 
                $data = $autocompleteService->search(
                    Supplier::class,
                    ['company_name'],
                    $request->search,
                    ['id', 'company_name'],
                    30
                );   
                break;
            case 'broker': 
                $data = $autocompleteService->search(
                    Broker::class,
                    ['broker_name'],
                    $request->search,
                    ['id', 'broker_name'],
                    30
                );    
                break;
            case 'employee': 
                $data = $autocompleteService->search(
                    Employee::class,
                    ['full_name'],
                    $request->search,
                    ['id', 'full_name'],
                    30
                );   
                break;
        }
        
        

        
        return response()->json($data);
    }
}
