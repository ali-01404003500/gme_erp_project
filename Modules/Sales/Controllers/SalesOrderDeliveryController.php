<?php

namespace Modules\Sales\Controllers;


use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderDelivery;
use Modules\Inventory\Services\StockService;
use Modules\Sales\Services\SalesOrderDeliveryService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
class SalesOrderDeliveryController extends Controller
{

    /**
     * Service variable
     *
     * @var SalesOrderDeliveryService
     */
    private $service; 

    /**
     * Service variable
     *
     * @var StockService
     */
    private $stockService;
    function __construct(SalesOrderDeliveryService $service, StockService $stockService)
    {
        $this->service = $service;
        $this->stockService = $stockService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['salesOrderDeliveries'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Sales::sales-order-deliveries.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('sales_order_deliveries_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Sales::sales-order-deliveries.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['salesOrders'] = SalesOrder::query()->get();
        if($request->has('sales_order_id')){
            $data['salesOrder'] = SalesOrder::find($request->sales_order_id);
        }
        return view('Sales::sales-order-deliveries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'sales_order_id' => 'required|exists:sales_orders,id',
        ]);
        $sODProductDetails = $request->validate([
            'product_id.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
        ]);
        $productDetails = $request->validate([
            'lot_no.*.*' => 'nullable|string',
            'lots_quantity.*.*' => 'nullable|numeric',
            'serial_no.*.*' => 'nullable|string',
        ]);
        // dd(
        //     $validate,
        //     $sODProductDetails,
        //     $productDetails
        // );
        $this->service->store($validate, $sODProductDetails, $productDetails);
        return redirect()->route('sales.sales-order-deliveries.index')->with('success', 'Sales Order Delivered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        // dd();
        $data['salesOrderDelivery'] = $this->service->show($id);

        return view("Sales::sales-order-deliveries.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesOrderDelivery $salesOrderDelivery)
    {
        $data['salesOrderDelivery'] = $salesOrderDelivery;
        //
        return view("Sales::sales-order-deliveries.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesOrderDelivery $salesOrderDelivery)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($salesOrderDelivery, $validate);

        return redirect()->route('salesOrderDeliverys.index')->with('success', 'SalesOrderDelivery updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrderDelivery $salesOrderDelivery)
    {
        $this->service->delete($salesOrderDelivery);
        return redirect()->route('salesOrderDeliverys.index')->with('success', 'SalesOrderDelivery deleted successfully.');
    }


    public function selectStock($product_id, Request $request){
        $data["product"]=ProductCatalog::find($product_id);
        $data['total_stock'] =  $this->stockService->countStockByProduct($product_id);
        if($data['product']){
            $data['stocks'] = $data["product"]->is_serial_product? $this->stockService->availableSerialsProductStocks($product_id): $this->stockService->availableLotsProductStocks($product_id);
        }
        // $data['stocks'] = $this->stockService->availableLootsProductStocks($product_id, $request->stock_id);
        return view('Sales::sales-order-deliveries.select-stock', $data);
    }
}
