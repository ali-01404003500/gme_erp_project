<?php

namespace Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\AccessControl\Branch;
use Modules\Inventory\Models\IssueProduct;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Settings\Unit;

use Modules\Inventory\Services\IssueProductService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\CRM\Models\Customer\Customer;

class IssueProductController extends Controller
{

    /**
     * Service variable
     *
     * @var IssueProductService
     */
    private $service;
    function __construct(IssueProductService $service)
    {
        $this->service = $service;
        $this->middleware('permited');

    }

    /**
     * Display a listing of the resource.
     */
    public function index(  Request $request)
    {
        $data['issue_products'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Inventory::issue-products.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('issue_product_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Inventory::issue-products.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['customers'] = Customer::activeCustomers()->get();
        $data['units'] = Unit::all();
        $data['products'] = Product::all();
        $data['branches'] = Branch::query()->get();
        $data['productCatalogs'] = ProductCatalog::all();
        $data['productTypes'] = ProductType::all();

        return view('Inventory::issue-products.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'issue_date' => 'required|date_format:m/d/Y',
            'branch_id' => 'required|exists:branches,id',
            'purpose_id' => 'nullable|string',
            'customer_id' => 'nullable|exists:customers,id',
            'order_number' => 'nullable|integer',
            'remarks' => 'nullable|string',
        ]);
        
        $issueProductDetails = $request->validate([
            'product_catalog_id' => 'required|array|min:1',
            'product_catalog_id.*' => 'required|exists:products,id',
            'sku' => 'required|array|min:1',
            'sku.*' => 'nullable|string',
            'unit_type_id' => 'required|array|min:1',
            'unit_type_id.*' => 'required|exists:units,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|numeric|min:1',

        ]);
        // dd( $validate,  $issueProductDetails);
        // dd( $issueProductDetails);
        $result = $this->service->store($validate,  $issueProductDetails);
        return redirect()->route('inv.issue-products.edit', $result['products']->id)->with('success', 'IssueProduct created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['customers'] = Customer::activeCustomers()->get();
        $data['units'] = Unit::all();
        $data['products'] = Product::all();

        $data['issueProduct'] = IssueProduct::find( $id);

        return view("Inventory::issue-products.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IssueProduct $issueProduct)
    {
        $data['issueProduct'] = $issueProduct;
        $data['customers'] = Customer::activeCustomers()->get();
        $data['units'] = Unit::all();
        $data['products'] = Product::all();
        //
        return view("Inventory::issue-products.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IssueProduct $issueProduct)
    {
         // dd($request->all());
         $validate = $request->validate([
           'issue_date' => 'required|date_format:m/d/Y',
            'branch_id' => 'required|exists:branches,id',
            'purpose_id' => 'nullable|string',
            'customer_id' => 'nullable|exists:customers,id',
            'order_number' => 'nullable|integer',
            'remarks' => 'nullable|string',
        ]);
        $issueProductDetails = $request->validate([
            'issue_product_detail_id' => 'array',
            'issue_product_detail_id.*' => 'nullable|exists:issue_product_details,id',
            'product_catalog_id' => 'array',
            'product_catalog_id.*' => 'required|exists:products,id',
            'product_name' => 'array',
            'product_name.*' => 'nullable|string',
            'sku' => 'array',
            'sku.*' => 'nullable|string',
            'unit_type_id' => 'array',
            'unit_type_id.*' => 'nullable|exists:units,id',
            'quantity' => 'array',
            'quantity.*' => 'nullable|numeric',
        ]);
        $result = $this->service->update($issueProduct, $validate,  $issueProductDetails);

        return redirect()->route('inv.issue-products.edit', $result['products']->id)->with('success', 'IssueProduct created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IssueProduct $issueProduct)
    {
        $this->service->delete($issueProduct);
        return redirect()->route('inv.issue-products.index')->with('success', 'IssueProduct deleted successfully.');
    }
}
