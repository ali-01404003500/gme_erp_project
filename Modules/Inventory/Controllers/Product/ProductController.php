<?php

namespace Modules\Inventory\Controllers\Product;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Settings\Tag;
use Modules\Inventory\Models\Settings\Unit;
use Illuminate\Http\Request;

use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\Inventory\Services\ProductService;

class ProductController extends Controller
{

    /**
     * Service variable
     *
     * @var ProductService
     */
    private $service; 
    function __construct(ProductService $service)
    {
        $this->service = $service;
        $this->middleware('permited')->except(['convertImageToBase64','search', 'countProduct']);
        $this->middleware('permitedSlug:dashboard')->only(['countProduct']);

        //ignore search method in middleware
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['products'] = $this->service->getAll();

        return view("Inventory::products.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data = $this->service->create($request);
        $data['productCatalog'] = ProductCatalog::with("productType")->find($request->product_catalog_id); 
        $product = Product::where('product_catalog_id', $request->product_catalog_id)->first();
        $data['broker_price'] = ProductCatalog::where( 'id', $product->product_catalog_id)->value('broker_price');

        if($product) {
            $data['product'] = $product;
            return view('Inventory::products.edit', $data);
        }
        return view('Inventory::products.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $new_validate = $request->validate([
            'product_catalog_id' => 'required|exists:product_catalogs,id',
            'last_cost_price' => 'nullable|numeric',
            'remainder_quantity' => 'nullable|numeric',
            'product_status' => 'nullable|max:255',
            'discount_type' => 'nullable|max:255',
            'product_tag_id' => 'nullable|exists:tags,id',
            'max_sales_quantity' => 'nullable|numeric',
            'total_sales_qty' => 'nullable|numeric',
            'applied_type' => 'nullable|max:255',
            'inv_no' => 'nullable|max:255',
            'status' => 'required|max:255',
            'max_purchase_quantity' => 'nullable|numeric',
            'total_purchase_qty' => 'nullable|numeric',
            'last_purchase_price' => 'nullable|numeric',
            'stock_info' => 'required|max:255',
            'min_discount' => 'nullable|numeric',
            'max_discount' => 'nullable|numeric',
            'dollar_price' => 'nullable|numeric',
            'hs_code' => 'nullable|max:255',
        ]);

        $broker_price_validation = $request->validate([
            'broker_price' => 'nullable|numeric',
        ]);
        // $new_validate = $request->validate([
        //     'product_type_id' => 'required|exists:product_types,id',
        //     'product_catalog_id' => 'nullable|exists:product_catalogs,id',
        //     'description' => 'nullable|max:255',
        //     'type' => 'nullable|max:255',
        //     'cost_price' => 'nullable|numeric',
        //     'stock_quantity' => 'nullable|numeric',
        //     'remainder_quantity' => 'nullable|numeric',
        //     'mrp' => 'nullable|numeric',
        //     'landed_price' => 'nullable|numeric',
        //     'transportation_cost' => 'nullable|numeric',
        //     'vat' => 'nullable|numeric',
        //     'tax' => 'nullable|numeric',
        //     'misc' => 'nullable|numeric',
        //     'total_price' => 'nullable|numeric',
        //     'max_sales_qty' => 'nullable|numeric',
        //     'total_sales_qty' => 'nullable|numeric',
        //     'applied_type' => 'nullable|max:255',
        //     'inv_no' => 'nullable|max:255',
        //     'stock' => 'nullable|max:255',
        //     'rule_status' => 'nullable|max:255',
        //     'start_date' => 'nullable|date',
        //     'stop_date' => 'nullable|date',
        //     'max_purchase_qty' => 'nullable|numeric',
        //     'total_purchase_qty' => 'nullable|numeric',
        //     'last_purchase_price' => 'nullable|numeric',
        //     'stock_status' => 'nullable|numeric',
        //     'remarks' => 'nullable|max:255',
        //     'discount_type' => 'nullable|max:255',
        //     'product_tag_id' => 'nullable|exists:tags,id',
        //     "hs_code" => "nullable|max:255",
        // ]);
     

        
        
        $this->service->store($new_validate, $broker_price_validation);
        return redirect()->route('inv.products.create', ['product_catalog_id' => $request->product_catalog_id])->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    // public function show( $id)
    // {
    //     $data['product'] = $this->service->show($id);

    //     return view("Inventory::products.show", $data);
    // }



        /**
     * Convert image to base64 string
     *
     * @param string $path
     * @return string|null
     */
   private function convertImageToBase64($path)
   {
       $fileContents = file_exists($path) ? file_get_contents($path) : null;

       if ($fileContents !== false) {
           $type = pathinfo($path, PATHINFO_EXTENSION);
           $base64 = 'data:image/' . $type . ';base64,' . base64_encode($fileContents);
           return $base64;
       }

       return null;
   }

   /**
    * Display the specified resource.
    */
   public function show($id, Request $request)
{
   $product = $this->service->show($id);

   $data['product'] = $product;

   if ($request->export == "pdf") {
       set_time_limit(1000);
       $html = view('Inventory::products.view', $data)->render();

       // Set Dompdf options
       $options = new Options();
       $options->setIsHtml5ParserEnabled(true);
       $options->setIsRemoteEnabled(true);
       
       $dompdf = new Dompdf($options);
       $dompdf->loadHtml($html);
       $dompdf->setPaper('A4', 'portrait');
       $dompdf->render();

       return $dompdf->stream('product_' . $data['product']->company_name . '.pdf', ['Attachment' => false]);
   }

   return view("Inventory::products.show", $data);
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $data['product'] = $product;
        $data['product_types'] = ProductType::query()->where('status', 1)->get();
        $data['product_catalogs'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();

        $data['broker_price'] = ProductCatalog::where( 'id', $product->product_catalog_id)->value('broker_price');

        $data['brands'] = Brand::all();
        $data['units'] = Unit::all();
        $data['tags'] = Tag::all();
         //
        return view("Inventory::products.edit", $data); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // dd($request->all());
        $validate = $request->validate([
            'product_catalog_id' => 'required|exists:product_catalogs,id',
            'last_cost_price' => 'nullable|numeric', 
            'remainder_quantity' => 'nullable|numeric',
            'product_status' => 'nullable|max:255',
            'discount_type' => 'nullable|max:255',
            'product_tag_id' => 'nullable|exists:tags,id',
            'max_sales_quantity' => 'nullable|numeric',
            'total_sales_qty' => 'nullable|numeric',
            'applied_type' => 'nullable|max:255',
            'inv_no' => 'nullable|max:255',
            'status' => 'required|max:255',
            'max_purchase_quantity' => 'nullable|numeric',
            'total_purchase_qty' => 'nullable|numeric',
            'last_purchase_price' => 'nullable|numeric',
            'stock_info' => 'required|max:255',
            'min_discount' => 'nullable|numeric',
            'max_discount' => 'nullable|numeric',
            'dollar_price' => 'nullable|numeric',
            'hs_code' => 'nullable|max:255',
        ]);
        $broker_price_validation = $request->validate([
            'broker_price' => 'nullable|numeric',
        ]);

        $this->service->update($product, $validate, $broker_price_validation);

        $redirectUrl = route('inv.products.edit', $product->id);
        if ($request->has('active_tab')) {
            $redirectUrl .= $request->input('active_tab');
        }

        return redirect($redirectUrl)->with('success', 'Product setting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->service->delete($product);
        return redirect()->route('inv.products.index')->with('success', 'Product deleted successfully.'); 
    }

    public function search(Request $request)
    {
        $data['products'] = $this->service->search($request->input('q'));
        return view("Inventory::products.index", $data);
    }

    
    
}
