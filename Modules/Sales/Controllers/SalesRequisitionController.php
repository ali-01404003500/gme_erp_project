<?php

namespace Modules\Sales\Controllers;


use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\Courier;
use Modules\Sales\Models\SalesRequisition;
use Modules\Sales\Services\SalesRequisitionService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\Account\Models\Setup\Bank;
use Modules\CRM\Models\Customer\Customer;
use Modules\LocationManager\Models\Area;

class SalesRequisitionController extends Controller
{

    /**
     * Service variable
     *
     * @var SalesRequisitionService
     */
    private $service;
    function __construct(SalesRequisitionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['salesRequisitions'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();
        $data['customers'] = Customer::activeCustomers()->get();


        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Sales::sales-requisition.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('sales_requisition_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Sales::sales-requisition.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['products'] = ProductCatalog::all();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['couriers'] = Courier::get();
        $data['areas'] = Area::get();
        $data['banks'] = Bank::all();
        return view('Sales::sales-requisition.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'additional_phone' => 'nullable|string',
            'invoice_date' => 'required|date',
            'delivery_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'percentage' => 'nullable|numeric',
            'vat' => 'required|numeric',
            'total' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'is_shipment' => 'nullable|boolean',
            'status' => 'nullable|string',
            'is_courier' => 'nullable|boolean',
        ]);


        $salesRequisitionDetails = $request->validate([
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'unit_discount.*' => 'required|numeric',
            'total_discount.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
        ]);
        $salesOrderShipments = [];
        if ($validate['is_shipment'] ?? false) {
            $salesOrderShipments = $request->validate([
                'courier_id' => ($validate['is_courier'] ?? false) ? 'required|exists:couriers,id' : 'nullable|exists:couriers,id',
                'area_id' => 'required',
                'address' => 'required|string',
                'contact_person_name' => 'required|string',
                'contact_person_number' => 'required|string',
                'condition' => 'nullable|in:on,off',
                'additional_amount' => ($request->input('condition') == 'on') ? 'required|numeric' : 'nullable|numeric',
                'condition_remarks' => ($request->input('condition') == 'on') ? 'required|string' : 'nullable|string',
            ]);
        } else {
            $validate['is_shipment'] = 0;
        }


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


        // dd($validate);
        $result = $this->service->store($validate, $salesRequisitionDetails, $salesOrderShipments, $payments);
        return redirect()->route('sales.sales-requisitions.edit', $result['salesRequisition']->id)->with('success', 'SalesRequisition created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $data['company_info'] = CompanyInfo::first();
        $data['salesRequisition'] = $this->service->show($id);

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Sales::sales-requisition.view', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);


            $dompdf = new Dompdf($options);

            $fontDir = 'assets/fonts/';
            $fontName = 'SolaimanLipi';
            $fontFile = $fontDir . 'SolaimanLipi.ttf';

            // Include font in DOMPDF's font cache
            $dompdf->getOptions()->set('isFontSubsettingEnabled', true);
            $fontMetrics = $dompdf->getFontMetrics();
            $fontMetrics->get_font($fontFile, 'SolaimanLipi');
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfName = 'sales_requisition_' . $data['salesRequisition']->company_name . '.pdf';
            return $dompdf->stream($pdfName, ['Attachment' => false]);
        }

        return view("Sales::sales-requisition.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $salesRequisition = $this->service->show($id);
        $data['salesRequisition'] = $salesRequisition;
        $data['products'] = ProductCatalog::all();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['couriers'] = Courier::get();
        $data['areas'] = Area::where('id', $salesRequisition->customer->company_place_id)->get();
        return view("Sales::sales-requisition.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesRequisition $salesRequisition)
    {

        // dd($request->all());
        $validate = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'additional_phone' => 'nullable|string',
            'invoice_date' => 'required|date',
            'delivery_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'discount' => 'required|numeric',
            'percentage' => 'nullable|numeric',
            'total' => 'required|numeric',
            'net_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'is_shipment' => 'nullable|boolean',
            'status' => 'nullable|string',
            'is_courier' => 'nullable|boolean',
            'verify_remark' => 'nullable|string',
            'approve_remark' => 'nullable|string',
            'is_urgent_approval' => 'nullable|boolean',
        ]);


        $salesRequisitionDetails = $request->validate([
            'product_ids.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'unit_discount.*' => 'required|numeric',
            'total_discount.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
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

        $salesOrderShipments = [];
        if ($validate['is_shipment'] ?? false) {
            $salesOrderShipments = $request->validate([
                'courier_id' => ($validate['is_courier'] ?? false) ? 'required|exists:couriers,id' : 'nullable|exists:couriers,id',
                'area_id' => 'required',
                'address' => 'required|string',
                'contact_person_name' => 'required|string',
                'contact_person_number' => 'required|string',
                'condition' => 'nullable|in:on,off',
                'additional_amount' => ($request->input('condition') == 'on') ? 'required|numeric' : 'nullable|numeric',
                'condition_remarks' => ($request->input('condition') == 'on') ? 'required|string' : 'nullable|string',
            ]);
        } else {
            $validate['is_shipment'] = 0;
        }
        $this->service->update($salesRequisition, $validate, $salesRequisitionDetails, $salesOrderShipments, $payments);

        return redirect()->route('sales.sales-requisitions.edit', $salesRequisition->id)->with('success', 'SalesRequisition updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesRequisition $salesRequisition)
    {
        $this->service->delete($salesRequisition);
        return redirect()->route('sales.sales-requisitions.index')->with('success', 'SalesRequisition deleted successfully.');
    }


    public function saveToSalesOrder($id)
    {
        $salesRequisition = SalesRequisition::find($id);
        $orders = $this->service->saveToSalesOrder($salesRequisition);
        // dd($orders);
        return redirect()->route('sales.sales-orders.edit', $orders->id)->with('success', 'SalesRequisition converted to SalesOrder successfully.');
    }

}
