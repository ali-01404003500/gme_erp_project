<?php

namespace Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\AccessControl\Branch;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\ProductTransferRequest;
use Modules\Inventory\Models\Settings\Unit;
use Modules\Inventory\Services\ProductTransferRequestService;
use Modules\Inventory\Services\StockService;
use App\Services\Notifications\GeneralNotificationService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\CRM\Models\Customer\Customer;

class ProductTransferRequestController extends Controller
{

    /**
     * Service variable
     *
     * @var ProductTransferRequestService
     */
    private $service;
    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;
    /**
     * StockService variable
     *
     * @var StockService
     */
    private $stockService;
    function __construct(ProductTransferRequestService $service, GeneralNotificationService $generalNotificationService, StockService $stockService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
        $this->stockService = $stockService;
        $this->middleware('permited');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['productTransferRequests'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Inventory::product-transfer-requests.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('product_transfer_request_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Inventory::product-transfer-requests.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['branches'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all();
        $data['products'] = Product::all();
        $data['productCatalogs'] = ProductCatalog::all();
        $data['customers'] = Customer::activeCustomers()->get();

        return view('Inventory::product-transfer-requests.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'request_date' => 'required|date',
            'source_branch_id' => 'required|integer|exists:branches,id',
            'destination_branch_id' => 'required|integer|exists:branches,id',
            // 'purpose' => 'required|string|max:255',
            // 'customer_id' => 'nullable|integer|exists:customers,id',
            // 'order_number' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ]);

        $productRequestDetails = $request->validate([
            // 'product_type_id.*' => 'required|integer|exists:product_types,id',
            'product_catalog_id.*' => 'required|integer|exists:product_catalogs,id',
            // 'sku.*' => 'required|string|max:255',
            // 'unit_type_id.*' => 'required|integer|exists:units,id',
            'quantity.*' => 'required|numeric|min:1',
            // 'transfer_notes.*' => 'nullable|string|max:255',
        ]);
        // dd($validate,  $productRequestDetails);
        // Validate Stock Availability
        foreach ($productRequestDetails['product_catalog_id'] as $key => $productId) {
            $requestedQty = $productRequestDetails['quantity'][$key];
            $availableStock = $this->stockService->countStockByProductAndBranch($productId, $validate['source_branch_id']);
            if ($requestedQty > $availableStock) {
                // Fetch product name for better error message
                $productName = ProductCatalog::find($productId)->productName() ?? 'Product';
                return redirect()->back()->withErrors(['quantity' => "Requested quantity for {$productName} exceeds available stock ({$availableStock})."])->withInput();
            }
        }
        $productTransferRequest = $this->service->store($validate, $productRequestDetails);
        $this->generalNotificationService->store([
            'title' => 'New Product Transfer Request Added',
            'description' => 'New Product Transfer Request Added needed approval',
            'action' => $this->generalNotificationService->actionBuilder(ProductTransferRequestController::class, 'approve', [$productTransferRequest->id]),
        ], $this->generalNotificationService->getPermittedUsers('inv.product-transfer-requests.approve'));
        return redirect()->route('inv.product-transfer-requests.edit', $productTransferRequest->id)->with('success', 'ProductTransferRequest created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $data['company_info'] = CompanyInfo::first();
        $data['productTransferRequest'] = $this->service->show($id);

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Inventory::product-transfer-requests.view', $data)->render();

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

            $pdfName = 'Product_Transfer_Request_Invoice_' . $data['productTransferRequest']->id . '.pdf';
            return $dompdf->stream($pdfName, ['Attachment' => false, 'Title' => 'Product Transfer Request Invoice']);
        }

        return view("Inventory::product-transfer-requests.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductTransferRequest $productTransferRequest)
    {
        $data['productTransferRequest'] = $productTransferRequest;
        $data['branches'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all();
        $data['products'] = Product::all();
        $data['productCatalogs'] = ProductCatalog::all();
        $data['customers'] = Customer::activeCustomers()->get();
        //
        return view("Inventory::product-transfer-requests.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductTransferRequest $productTransferRequest)
    {
        // dd($request->all());
        $validate = $request->validate([
            'request_date' => 'required|date',
            'source_branch_id' => 'required|integer|exists:branches,id',
            'destination_branch_id' => 'required|integer|exists:branches,id',
            // 'purpose' => 'required|string|max:255',
            // 'customer_id' => 'nullable|integer|exists:customers,id',
            // 'order_number' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
            'status' => 'nullable|string'
        ]);

        $productRequestDetails = $request->validate([
            // 'product_type_id.*' => 'required|integer|exists:product_types,id',
            'product_catalog_id.*' => 'required|integer|exists:product_catalogs,id',
            // 'sku.*' => 'required|string|max:255',
            // 'unit_type_id.*' => 'required|integer|exists:units,id',
            'quantity.*' => 'required|numeric|min:1',
            // 'transfer_notes.*' => 'nullable|string|max:255',
            'product_transfer_request_detail_id.*' => 'nullable|integer|exists:product_transfer_request_details,id',
        ]);
        // Validate Stock Availability
        foreach ($productRequestDetails['product_catalog_id'] as $key => $productId) {
            $requestedQty = $productRequestDetails['quantity'][$key];
            $availableStock = $this->stockService->countStockByProductAndBranch($productId, $validate['source_branch_id']);
            if ($requestedQty > $availableStock) {
                $productName = ProductCatalog::find($productId)->productName() ?? 'Product';
                return redirect()->back()->withErrors(['quantity' => "Requested quantity for {$productName} exceeds available stock ({$availableStock})."])->withInput();
            }
        }

        $productTransferRequest = $this->service->update($productTransferRequest, $validate, $productRequestDetails);
        if ($validate['status'] == "approve") {
            $this->generalNotificationService->store([
                'title' => 'Product Transfer Request Approved',
                'description' => 'Product Transfer Request has been approved',
                'action' => '#', // Link to view if needed
            ], $this->generalNotificationService->getPermittedUsers('inv.product-transfers.create')); // Notify those who can create transfers
            return redirect()->route('inv.product-transfer-requests.edit', $productTransferRequest->id)->with('success', 'Product Transfer Request Approved successfully.');
        }
        if ($validate['status'] == "rejected") {
            return redirect()->route('inv.product-transfer-requests.index')->with('success', 'Product Transfer Request Rejected successfully.');
        }
        return redirect()->route('inv.product-transfer-requests.edit', $productTransferRequest->id)->with('success', 'ProductTransferRequest updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductTransferRequest $productTransferRequest)
    {
        $this->service->delete($productTransferRequest);
        return redirect()->route('inv.product-transfer-requests.index')->with('success', 'ProductTransferRequest deleted successfully.');
    }
}
