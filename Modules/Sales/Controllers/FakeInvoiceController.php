<?php

namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\FakeInvoice;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderDetails;
use Modules\Sales\Services\FakeInvoiceService;
use Illuminate\Http\Request;

class FakeInvoiceController extends Controller
{

    /**
     * Service variable
     *
     * @var FakeInvoiceService
     */
    private $service; 
    function __construct(FakeInvoiceService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['fakeInvoices'] = $this->service->getAll();
        $data['customers'] = Customer::activeCustomers()->get();

        return view("Sales::fake-invoices.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['invoices'] = SalesOrder::get();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['products'] = ProductCatalog::all();

        $data['salesOrder'] = SalesOrder::find($request->invoice_id);
        return view('Sales::fake-invoices.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
       $validate = $request->validate([
            'sales_order_id' => 'required|integer|exists:sales_orders,id',
            'customer_id' => 'required|integer|exists:customers,id',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'commission' => 'nullable|numeric',
            'total' => 'required|numeric',
            'vat' => 'nullable|numeric',
            'net_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $validate['commission'] = $validate['commission'] ?? 0;
        $validate['vat'] = $validate['vat'] ?? '0';



        $salesOrderDetails = $request->validate([
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'unit_discount.*' => 'required|numeric',
            'total_discount.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
        ]);
        $this->service->store($validate, $salesOrderDetails);
        return redirect()->route('sales.fake-invoices.index')->with('success', 'Fake Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['fakeInvoice'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        return view("Sales::fake-invoices.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FakeInvoice $fakeInvoice)
    {
        $data['fakeInvoice'] = $fakeInvoice;
        $data['customers'] = Customer::activeCustomers()->get();
        $data['products'] = ProductCatalog::all();
       
        return view("Sales::fake-invoices.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         $validate = $request->validate([
            'sales_order_id' => 'required|integer|exists:sales_orders,id',
            'customer_id' => 'required|integer|exists:customers,id',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'commission' => 'nullable|numeric',
            'total' => 'required|numeric',
            'vat' => 'nullable|numeric',
            'net_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $validate['commission'] = $validate['commission'] ?? 0;
        $validate['vat'] = $validate['vat'] ?? '0';



        $salesOrderDetails = $request->validate([
            'fake_invoice_detail_id.*' => 'nullable|integer|exists:fake_invoice_details,id',
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'unit_discount.*' => 'required|numeric',
            'total_discount.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
        ]);

        $fakeInvoice = $this->service->show($id);
        $this->service->update($fakeInvoice, $validate, $salesOrderDetails);

        return redirect()->route('sales.fake-invoices.index')->with('success', 'Fake Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fakeInvoice = $this->service->show($id);
        $this->service->delete($fakeInvoice);
        return redirect()->route('sales.fake-invoices.index')->with('success', 'Fake Invoice deleted successfully.');
    }
}
