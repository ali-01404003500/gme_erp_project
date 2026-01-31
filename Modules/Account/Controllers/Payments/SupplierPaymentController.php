<?php

namespace Modules\Account\Controllers\Payments;

use App\Http\Controllers\Controller;
use Modules\Account\Models\Payments\SupplierPayment;
use Modules\Account\Services\Payments\SupplierPaymentService;
use Illuminate\Http\Request;
use Modules\Account\Models\Account;
use Modules\Purchase\Models\Supplier;

class SupplierPaymentController extends Controller
{

    /**
     * Service variable
     *
     * @var SupplierPaymentService
     */
    private $service; 
    function __construct(SupplierPaymentService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['supplierPayments'] = $this->service->getAll();

        return view("Account::payments.supplier-payments.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)	
    {
        $data['suppliers'] = Supplier::all();
        $data['supplier'] = Supplier::with(['receives'=>function($q){
            $q->where('status', '1');
        }])->find($request->supplier_id);

        // dd($data['supplier']->receives->first()->receiveDetails);
        $data['receiver_accounts']  = Account::query()->whereIn('account_subsidiary_id', [1001, 1002])->get();
        return view('Account::payments.supplier-payments.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'supplier_id' => 'required|numeric|exists:suppliers,id',
            'total_vat' => 'nullable|numeric',
            'due_amount' => 'nullable|numeric',
            'advance_amount' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
            'previous_advance' => 'nullable|numeric',
            'account_id' => 'required|numeric|exists:accounts,id',
        ]);

        $receiveDetails = $request->validate([
            'invoice_vat.*' => 'nullable|numeric',
            'vat.*' => 'nullable|numeric',
            'paid.*' => 'nullable|numeric',
            'payable_amounts.*' => 'nullable|numeric',
            'pay_amount.*' => 'nullable|numeric',
            'receive_ids.*' => 'nullable|exists:purchase_order_receives,id',
        ]);

     
        // dd( , $validate, $request->all(), $invoiceDetailProduct);
        $this->service->store($validate, $receiveDetails);
        return redirect()->route('account.payments.supplier-payments.index')->with('success', 'SupplierPayment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['supplierPayment'] = $this->service->show($id);

        return view("Account::payments.supplier-payments.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierPayment $supplierPayment)
    {
        $data['supplierPayment'] = $supplierPayment;
        //
        return view("supplierPayments.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupplierPayment $supplierPayment)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($supplierPayment, $validate);

        return redirect()->route('supplierPayments.index')->with('success', 'SupplierPayment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierPayment $supplierPayment)
    {
        $this->service->delete($supplierPayment);
        return redirect()->route('supplierPayments.index')->with('success', 'SupplierPayment deleted successfully.');
    }
}
