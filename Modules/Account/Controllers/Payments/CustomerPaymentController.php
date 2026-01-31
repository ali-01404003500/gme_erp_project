<?php

namespace Modules\Account\Controllers\Payments;

use App\Http\Controllers\Controller;
use Modules\Account\Models\Payments\CustomerPayment;
use Modules\Account\Services\Payments\CustomerPaymentService;
use Illuminate\Http\Request;
use Modules\Account\Models\Account;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\CRM\Models\Customer\Customer;

class CustomerPaymentController extends Controller
{

    /**
     * Service variable
     *
     * @var CustomerPaymentService
     */
    private $service; 
    function __construct(CustomerPaymentService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['customerPayments'] = $this->service->getAll();

        return view("Account::payments.customer-payments.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['customers'] = Customer::activeCustomers()->get();
        $data['customer'] = Customer::with(
            ["invoices"=>function($q){
                $q->where('status', 'approved');
            }])
            ->find($request->customer_id);
        $data['receiver_accounts']  = Account::query()->whereIn('account_subsidiary_id', [1001, 1002])->get();
        return view('Account::payments.customer-payments.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd( $request->all());
        $validate = $request->validate([
            'customer_id' => 'required|numeric|exists:customers,id',
            'total_vat' => 'nullable|numeric',
            'due_amount' => 'nullable|numeric',
            'advance_amount' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
            'previous_advance' => 'nullable|numeric',
            'account_id' => 'required|numeric|exists:accounts,id',
        ]);

        $invoiceDetails = $request->validate([
            'invoice_vat.*' => 'nullable|numeric',
            'vat.*' => 'nullable|numeric',
            'paid.*' => 'nullable|numeric',
            'payable_amounts.*' => 'nullable|numeric',
            'pay_amount.*' => 'nullable|numeric',
            'invoice_ids.*' => 'nullable|exists:sale_invoices,id',
        ]);

        $invoiceDetailProduct= $request->validate([
            'product_ids.*.*' => 'required|numeric',
            'invoice_qtys.*.*' => 'required|numeric',
            'prices.*.*' => 'required|numeric',
            'unit_discount.*.*' => 'nullable|numeric',
            'quantities.*.*' => 'required|numeric',
        ]);
        // dd( , $validate, $request->all(), $invoiceDetailProduct);
        $this->service->store($validate, $invoiceDetails, $invoiceDetailProduct);
        return redirect()->route('account.payments.customer-payments.index')->with('success', 'CustomerPayment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['customerPayment'] = $this->service->show($id);

        return view("Account::payments.customer-payments.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerPayment $customerPayment)
    {
        $data['customerPayment'] = $customerPayment;
        //
        return view("Account::payments.customer-payments.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerPayment $customerPayment)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($customerPayment, $validate);

        return redirect()->route('customerPayments.index')->with('success', 'CustomerPayment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerPayment $customerPayment)
    {
        $this->service->delete($customerPayment);
        return redirect()->route('customerPayments.index')->with('success', 'CustomerPayment deleted successfully.');
    }
}
