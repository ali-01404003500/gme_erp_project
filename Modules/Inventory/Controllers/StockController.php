<?php

namespace Modules\Inventory\Controllers;

use App\Exports\stockInHandExport;
use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Stock;
use Modules\Inventory\Services\StockService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class StockController extends Controller
{

    /**
     * Service variable
     *
     * @var StockService
     */
    private $service; 
    function __construct(StockService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['stocks'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Inventory::stocks.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('stock_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("stocks.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stocks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->store($validate);
        return redirect()->route('stocks.index')->with('success', 'Stock created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['stock'] = $this->service->show($id);

        return view("stocks.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        $data['stock'] = $stock;
        //
        return view("stocks.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($stock, $validate);

        return redirect()->route('stocks.index')->with('success', 'Stock updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        $this->service->delete($stock);
        return redirect()->route('stocks.index')->with('success', 'Stock deleted successfully.');
    }


    function stockInHand(Request $request){
        $data['products'] = ProductCatalog::query()->where('status', 1)->get();
        $data['stocks'] = $this->service->stockInHand();
        $data['productCatalogs'] = ProductCatalog::all();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Inventory::stocks.stock-in-hand-pdf', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('stock_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }
        return view("Inventory::stocks.stock-in-hand", $data);
    }


    function productLedger($product_id)  {
        $data['stocks'] = $this->service->productLedger($product_id);
        return view('Inventory::stocks.stock-product-ledger', $data);
    }

    function productAvailableInBranch(Request $request){
        $stock = $this->service->countStockByProductAndBranch($request->product_id, $request->branch_id);
        return response()->json($stock);
    }

    public function export()
    {
        $filename = 'stock-in-hand-' . date('Y-m-d') . '.xls';

        return Excel::download(new stockInHandExport($this->service), $filename);
    }
}
 