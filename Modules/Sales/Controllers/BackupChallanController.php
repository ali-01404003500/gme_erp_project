<?php

namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\BackupChallan;
use Modules\Sales\Models\Courier;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderDetails;
use App\Services\Notifications\GeneralNotificationService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;
use Modules\LocationManager\Models\Area;
use Modules\Sales\Services\BackupChallanService;

class BackupChallanController extends Controller
{

    /**
     * Service variable
     *
     * @var BackupChallanService
     */
    private $service;
    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;
    function __construct(BackupChallanService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['customers'] = Customer::activeCustomers()->get();
        $data['backupChallans'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Sales::backup-challan.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('sales_order_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Sales::backup-challan.index", $data);
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
        return view('Sales::backup-challan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $backupChallan = $this->getBackupChallanNumber();

        $validate = $request->validate([
            'remaining_date' => 'required|date',
            'invoice_date' => 'required|date',
            'type' => 'required|string',
            'customer_id' => 'required|exists:customers,id',
            'invoice_no' => 'nullable|string',
            'total_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'is_shipment' => 'nullable|boolean',
        ]);
        $backupChallanDetails = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|exists:product_catalogs,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:1',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:1',
        ]);

        $backupChallanShipments = [];
        if ($validate['is_shipment'] ?? false == true) {
            $backupChallanShipments = $request->validate([
                'courier_id' => 'required|exists:couriers,id',
                'area_id' => 'required',
                'address' => 'required|string',
                'contact_person_name' => 'required|string',
                'contact_person_number' => 'required|string',
            ]);
        }
        $validate['invoice_id'] = $backupChallan;

        $result = $this->service->store($validate, $backupChallanDetails, $backupChallanShipments);
        $this->generalNotificationService->store([
            'title' => 'New Backup Challan Added',
            'description' => 'New Backup Challan Added needed approval',
            'action' => $this->generalNotificationService->actionBuilder(BackupChallanController::class, 'approve', [$result['backupChallan']->id]),
        ], $this->generalNotificationService->getPermittedUsers('sales.backup-challans.approve'));
        return redirect()->route('sales.backup-challans.edit', $result['backupChallan']->id)->with('success', 'BackupChallan created successfully.');
    }
    public function getBackupChallanNumber()
    {
        $today = date('Y-m-d');
        $authUser = auth()->user();
        $authUserBranch = $authUser->branch_id;
        $authUserBranchType = $authUser->branch->branch_type_id;

        $challansToday = BackupChallan::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser->id)
            ->count();

        // Generate Backup Challan number with the appropriate format
        $challanNumber = sprintf(
            'SCT-%02d-SC-%02d-%s-USR-%06d-BS-%05d',
            $authUserBranch,
            $authUserBranchType,
            date('Ymd'),
            $authUser->id,
            $challansToday + 1
        );

        return $challanNumber;
    }
    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $data['company_info'] = CompanyInfo::first();

        $data['customers'] = Customer::activeCustomers()->get();

        $data['backupChallan'] = $this->service->show($id);

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Sales::backup-challan.view', $data)->render();

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

            $pdfName = 'backup_challan_' . $data['backupChallan']->company_name . '.pdf';
            return $dompdf->stream($pdfName, ['Attachment' => false]);
        }

        return view("Sales::backup-challan.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BackupChallan $backupChallan)
    {
        $data['backupChallan'] = $backupChallan;
        $data['products'] = ProductCatalog::all();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['couriers'] = Courier::get();
        $data['areas'] = Area::where('id', $backupChallan->customer->company_place_id)->get();
        return view("Sales::backup-challan.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BackupChallan $backupChallan)
    {
        $validate = $request->validate([
            'remaining_date' => 'required|date',
            'invoice_date' => 'required|date',
            'type' => 'required|string',
            'customer_id' => 'required|exists:customers,id',
            'invoice_no' => 'nullable|string',
            'total_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'is_shipment' => 'nullable',
        ]);
        $validate['is_shipment'] = $validate['is_shipment'] ?? 0;

        $backupChallanDetails = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|exists:product_catalogs,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:1',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:1',
        ]);

        $backupChallanShipments = $request->validate([
            'courier_id' => 'nullable|exists:couriers,id',
            'area_id' => 'nullable',
            'address' => 'nullable|string',
            'contact_person_name' => 'nullable|string',
            'contact_person_number' => 'nullable|string',
        ]);
        $this->service->update($backupChallan, $validate, $backupChallanDetails, $backupChallanShipments);

        return redirect()->route('sales.backup-challans.edit', $backupChallan->id)->with('success', 'BackupChallan updated successfully.');
    }


    public function approve($id)
    {
        $data['backupChallan'] = $this->service->show($id);

        $data['products'] = ProductCatalog::all();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['couriers'] = Courier::get();
        $data['areas'] = Area::where('id', $data['backupChallan']->customer->company_place_id)->get();
        return view("Sales::backup-challan.approve", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function approveStore(Request $request, $id)
    {
        $backupChallan = $this->service->show($id);
        $validate = $request->validate([
            'remaining_date' => 'required|date',
            'invoice_date' => 'required|date',
            'type' => 'required|string',
            'customer_id' => 'required|exists:customers,id',
            'invoice_no' => 'nullable|string',
            'total_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'is_shipment' => 'nullable',
            'status' => 'required',
        ]);
        $validate['is_shipment'] = $validate['is_shipment'] ?? 0;

        $validate['approved_by'] = Auth::user()->id;

        $backupChallanDetails = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|exists:product_catalogs,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:1',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:1',
        ]);

        $backupChallanShipments = $request->validate([
            'courier_id' => 'nullable|exists:couriers,id',
            'area_id' => 'nullable',
            'address' => 'nullable|string',
            'contact_person_name' => 'nullable|string',
            'contact_person_number' => 'nullable|string',
        ]);
        $this->service->update($backupChallan, $validate, $backupChallanDetails, $backupChallanShipments);

        if ($validate['status'] == 'approved') {
            return redirect()->route('sales.backup-challans.index')->with('success', 'BackupChallan approved successfully.');
        } else {
            return redirect()->route('sales.backup-challans.index')->with('success', 'BackupChallan Rejected successfully.');
        }
    }


    public function salesOrder(Request $request)
    {
        $backupChallan = BackupChallan::find($request->id);


        $salesOrder = SalesOrder::create([
            'customer_id' => $backupChallan->customer_id,
            'invoice_date' => today()->format('Y-m-d'),
            'total_amount' => $backupChallan->total_amount,
            'discount' => $backupChallan->discount ?? 0,
            'commission' => 0,
            'total' => $backupChallan->total_amount ?? 0,
            'vat' => 0,
            'net_amount' => $backupChallan->total_amount,
            'remarks' => $backupChallan->remarks,
            'status' => 'delivered',
            'source_type' => BackupChallan::class,
            'source_id' => $backupChallan->id,
        ]);


        foreach ($backupChallan->backupChallanDetails as $item) {
            SalesOrderDetails::create([
                'sales_order_id' => $salesOrder->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'unit_discount' => $item->unit_discount ?? 0,
                'total_discount' => $item->total_discount ?? 0,
                'amount' => $item->amount,
                'is_offers_product' => false, // Default value for non-offer products
            ]);
        }

        $backupChallan->update([
            'status' => "Sales"
        ]);

        return redirect()->route('sales.sales-orders.index')->with('success', 'SalesOrder created successfully for Challan.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BackupChallan $backupChallan)
    {
        $this->service->delete($backupChallan);
        return redirect()->route('sales.backup-challans.index')->with('success', 'BackupChallan deleted successfully.');
    }


    public function saveToSalesOrder($id)
    {
        $backupChallan = BackupChallan::findOrFail($id);
        $salesOrder = $this->service->saveToSalesOrder($backupChallan);

        return redirect()->route('sales.sales-orders.edit', $salesOrder->id)->with('success', 'Backup Challan converted to Sales Order successfully.');
    }

    public function sendToDelivery($id)
    {
        $backupChallan = BackupChallan::findOrFail($id);
        $delivery = $this->service->sendToDelivery($backupChallan);

        return redirect()->route('sales.deliveries.create', ['delivery_id' => $delivery->id])->with('success', 'Backup Challan sent to delivery successfully.');
    }
}
