<?php

namespace Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Services\AutocompleteService;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Settings\Tag;
use Modules\Inventory\Models\Settings\Unit;
use Modules\Inventory\Services\ProductCatalogService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ProductCatalogController extends Controller
{

    /**
     * Service variable
     *
     * @var ProductCatalogService
     */
    private $service; 
    function __construct(ProductCatalogService $service)
    {
        $this->service = $service;
        $this->middleware('permited');
        $this->middleware('permited')->except(['productNameAutocomplete']);

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['productCatalogs'] = $this->service->getAll(); 
        $data['employees'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();
        $data['productBrands'] = Brand::all();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Inventory::product-catalogs.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('product_information_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }


        return view("Inventory::product-catalogs.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['units'] = Unit::all();
        $data['brands'] = Brand::all();
        $data['productTypes'] = ProductType::all();
        $data['tags'] = Tag::all();

        return view('Inventory::product-catalogs.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        
        $validate = $request->validate([
            //validate rules
            'product_type_id' => 'required|exists:product_types,id',
            'product_brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255|unique:product_catalogs,name,NULL,id,deleted_at,NULL,model,'.$request->model.',product_brand_id,'.$request->product_brand_id, 
            'model' => 'nullable|string|max:255',
            'mrp' => 'required|numeric',
            'unit_type_id' => 'required|exists:units,id',
            'product_tag_id' => 'nullable|exists:tags,id',
            'product_origin' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'is_serial' => 'nullable|string|max:255',
            'is_expire_date' => 'nullable|string|max:255',
            'is_warranty' => 'nullable|string|max:255',
            'warranty_period' => 'nullable|string|max:255',
            'warranty_period_input' => 'nullable|numeric',
            'force_barcode_scan' => 'nullable|string|max:255',
            'ecommerce_product' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:65535',
            'profile_image_upload' => 'nullable|string',
            'image_uploads' => 'nullable|array|min:1',
            'image_uploads.*' => 'nullable|string',
            'catalog_file' => 'nullable|string',
            'price_list_file' => 'nullable|string',
            'product_code' => 'nullable',
        ]);
        $productCatalog = $this->service->store($validate);
        return redirect()->route('inv.product-catalogs.edit', $productCatalog->id)->with('success', 'ProductCatalog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $productCatalog = $this->service->show($id);
        $data['productCatalog'] = $productCatalog;
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Inventory::product-catalogs.view', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('product-catalog_' . $productCatalog->company_name . '.pdf', ['Attachment' => false]);
        }

        return view("Inventory::product-catalogs.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCatalog $productCatalog)
    {
        $data['productCatalog'] = $productCatalog;
        $data['units'] = Unit::all();
        $data['brands'] = Brand::all();
        $data['productTypes'] = ProductType::all();
        $data['tags'] = Tag::all();
        //
        return view("Inventory::product-catalogs.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCatalog $productCatalog)
    {
        // dd($request->all());
        $validate = $request->validate([
            //validate rules
            'product_type_id' => 'required|exists:product_types,id',
            'product_brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'mrp' => 'required|numeric',
            'unit_type_id' => 'required|exists:units,id',
            'product_tag_id' => 'nullable|exists:tags,id',
            'product_origin' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'is_serial' => 'nullable|string|max:255',
            'is_expire_date' => 'nullable|string|max:255',
            'is_warranty' => 'nullable|string|max:255',
            'warranty_period' => 'nullable|string|max:255',
            'warranty_period_input' => 'nullable|numeric',
            'force_barcode_scan' => 'nullable|string|max:255',
            'ecommerce_product' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:65535',
            'profile_image_upload' => 'nullable|string',
            'image_uploads' => 'nullable|array|min:1',
            'image_uploads.*' => 'nullable|string',
            'catalog_file' => 'nullable|string',
            'price_list_file' => 'nullable|string',
            'product_code' => 'nullable',
        ]);
       
        $productCatalog = $this->service->update($productCatalog, $validate);

        return redirect()->route('inv.product-catalogs.edit', $productCatalog->id)->with('success', 'ProductCatalog updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCatalog $productCatalog)
    {
        $this->service->delete($productCatalog);
        return redirect()->route('inv.product-catalogs.index')->with('success', 'ProductCatalog deleted successfully.');
    }

    public function countProduct(){
        return response()->json(["count" => $this->service->countProduct(),"current_month" =>$this->service->countProductCurrentMonth(), "previous_month" => $this->service->countProductPreviousMonth()]);
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $fileContents = file($file->getPathname());

        $header = str_getcsv(array_shift($fileContents));

        foreach ($fileContents as $line) {
            $data = array_combine($header, str_getcsv($line));

            $productCatalog = ProductCatalog::where('name', $data['product_catalog'])->first();
            $tag = Tag::where('name', $data['product_tag'])->first();

            $productData = [
                'product_catalog_id' => $productCatalog->id,
                'dollar_price' => $data['dollar_price'],
                'hs_code' => $data['hs_code'],
                'last_cost_price' => $data['last_cost_price'],
                'remainder_quantity' => $data['remainder_quantity'],
                'product_status' => $data['product_status'],
                'discount_type' => $data['discount_type'],
                'min_discount' => $data['min_discount'],
                'max_discount' => $data['max_discount'],
                'product_tag_id' => $tag->id,
                'max_sales_quantity' => $data['max_sales_quantity'],
                'total_sales_qty' => $data['total_sales_qty'],
                'applied_type' => $data['applied_type'],
                'inv_no' => $data['inv_no'],
                'status' => $data['status'],
                'stock_info' => $data['stock_info'],
                'max_purchase_quantity' => $data['max_purchase_quantity'],
                'total_purchase_qty' => $data['total_purchase_qty'],
                'last_purchase_price' => $data['last_purchase_price'],
            ];

            \Modules\Inventory\Models\Product::create($productData);
        }

        return redirect()->route('inv.products.index')->with('success', 'Products imported successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $filename = $request->file('csv_file')->getClientOriginalName();
        $path = $request->file('csv_file')->storeAs('public', $filename);

        $file = fopen(storage_path('app/' . $path), 'r');
        $header = fgetcsv($file);
        $totalRows = 0;
        $skippedRows = 0;
        $successfulImports = 0;
        try {
            DB::beginTransaction();
            $products = [];
            while ($row = fgetcsv($file)) {
                $totalRows++;
                // dd($row);
                // Skip empty rows
                $data = array_combine($header, $row);

                // Check for required fields before processing
                if (
                    empty($data['product_type']) ||
                    empty($data['product_brand']) ||
                    empty($data['unit_type']) ||
                    empty($data['name']) ||
                    empty($data['mrp'])||$data['product_type'] == ''
                ) {
                    // Log and skip rows with missing required fields
                    \Illuminate\Support\Facades\Log::warning('Skipping row due to missing required fields', [
                        'row' => $row,
                        'data' => $data,
                    ]);
                    $skippedRows++;
                    continue;
                }
                // dd($data);
                // if(){
                $productType = \Modules\Inventory\Models\Product\Settings\ProductType::firstOrCreate(['name' => $data['product_type'] ?? null],['code' => Str::slug($data['product_type'])]);
                $brand = \Modules\Inventory\Models\Product\Settings\Brand::firstOrCreate(['name' => $data['product_brand'] ?? null], ['code' => Str::slug($data['product_brand']), 'supplier_id' => 1]);
                $unit = \Modules\Inventory\Models\Settings\Unit::firstOrCreate(['name' => $data['unit_type'] ?? null]);
                $tag = \Modules\Inventory\Models\Settings\Tag::firstOrCreate(['name' => $data['product_tag'] ?? null]);

                $validatedData = validator([
                    'product_type_id' => $productType->id,
                    'product_brand_id' => $brand ? $brand->id : null,
                    'name' => $data['name'] ?? null,
                    'model' => $data['model'] ?? null,
                    'mrp' => $data['mrp'] ?? null,
                    'unit_type_id' => $unit->id,
                    'product_tag_id' => $tag ? $tag->id : null,
                    'product_origin' => $data['product_origin'] ?? null,
                    'status' => $data['status'] ?? null,
                    'is_serial' => $data['is_serial'] ?? null,
                    'is_expire_date' => $data['is_expire_date'] ?? null,
                    'is_warranty' => $data['is_warranty'] ?? null,
                    'warranty_period' => $data['warranty_period'] ?? null,
                    'warranty_period_input' => (isset($data['warranty_period_input']) && $data['warranty_period_input'] !== '') ? $data['warranty_period_input'] : null,
                    'force_barcode_scan' => $data['force_barcode_scan'] ?? null,
                    'ecommerce_product' => $data['ecommerce_product'] ?? null,
                    'description' => $data['description'] ?? null,
                    'product_code' => $data['product_code'] ?? null,
                ],
                [
                    'product_code' => 'nullable|string|max:255',
                    'product_type_id' => 'required|exists:product_types,id',
                    'product_brand_id' => 'nullable|exists:brands,id',
                    'name' => 'required|string|max:255',
                    'model' => 'nullable|string|max:255',
                    'mrp' => 'required|numeric',
                    'unit_type_id' => 'required|exists:units,id',
                    'product_tag_id' => 'nullable|exists:tags,id',
                    'product_origin' => 'nullable|string|max:255',
                    'status' => 'nullable|string|max:255',
                    'is_serial' => 'nullable|string|max:255',
                    'is_expire_date' => 'nullable|string|max:255',
                    'is_warranty' => 'nullable|string|max:255',
                    'warranty_period' => 'nullable|string|max:255',
                    'warranty_period_input' => 'nullable|numeric',
                    'force_barcode_scan' => 'nullable|string|max:255',
                    'ecommerce_product' => 'nullable|string|max:255',
                    'description' => 'nullable|string|max:65535',
                ])->validate();

                // Create or update the product catalog
                $productCatalog = ProductCatalog::create($validatedData);

                $productData = validator([
                    'dollar_price' => $data['dollar_price'] ?? null,
                    'hs_code' => $data['hs_code'] ?? null,
                    'last_cost_price' => $data['last_cost_price'] ?? null,
                    'remainder_quantity' => $data['remainder_quantity'] ?? null,
                    'product_status' => $data['product_status'] ?? null,
                    'discount_type' => $data['discount_type'] ?? null,
                    'min_discount' => $data['min_discount'] ?? null,
                    'max_discount' => $data['max_discount'] ?? null,
                    'max_sales_quantity' => $data['max_sales_quantity'] ?? null,
                    'total_sales_qty' => $data['total_sales_qty'] ?? null,
                    'applied_type' => $data['applied_type'] ?? null,
                    'inv_no' => $data['inv_no'] ?? null,
                    'stock_info' => $data['stock_info'] ?? null,
                    'max_purchase_quantity' => $data['max_purchase_quantity'] ?? null,
                    'total_purchase_qty' => $data['total_purchase_qty'] ?? null,
                    'last_purchase_price' => $data['last_purchase_price'] ?? null,
                    'status' => 'running',
                    'product_tag_id' => $tag ? $tag->id : null
                ], [
                    'dollar_price' => 'nullable|numeric',
                    'hs_code' => 'nullable|string|max:255',
                    'last_cost_price' => 'nullable|numeric',
                    'remainder_quantity' => 'nullable|numeric',
                    'product_status' => 'nullable|string|in:active,inactive',
                    'discount_type' => 'nullable|string|in:Percentage,Fixed,NA',
                    'min_discount' => 'nullable|numeric',
                    'max_discount' => 'nullable|numeric',
                    'max_sales_quantity' => 'nullable|numeric',
                    'total_sales_qty' => 'nullable|numeric',
                    'applied_type' => 'nullable|string|max:255',
                    'inv_no' => 'nullable|string|max:255',
                    'stock_info' => 'required|string|in:available,stock_out',
                    'max_purchase_quantity' => 'nullable|numeric',
                    'total_purchase_qty' => 'nullable|numeric',
                    'last_purchase_price' => 'nullable|numeric',
                    'status' => 'required|string|in:running,stopped',
                    'product_tag_id' => 'nullable|exists:tags,id'
                ])->validate();
                

                $productCatalog->product()->create($productData);
                $products[]= $productCatalog;
                $successfulImports++;
            }
            // dd($products);
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error importing product catalogs: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error importing product catalogs: ' . $e->getMessage(),
            ], 500);
        }
        fclose($file);

        $message = "Product catalogs imported successfully. Total rows: $totalRows, Skipped: $skippedRows, Successful: $successfulImports.";
        return redirect()->route('inv.product-catalogs.index')->with('success', $message);
    }

    public function downloadSampleCSV()
    {
        return response()->download(public_path('templates/product_catalog_sample.csv'), 'product_catalog_sample.csv');
    }


    public function updateCatalogMrp(Request $request, $id)
    {
        $request->validate([
            'mrp' => 'required|numeric',
        ]);

        $productCatalog = ProductCatalog::findOrFail($id);
        $productCatalog->mrp = $request->input('mrp');
        $productCatalog->save();
        return response()->json([
            'mrp' => $productCatalog->mrp,
            'message' => 'MRP updated successfully.',
        ]);

    }

    /**
 * Display catalogue report page
 */
    /**
 * Display catalogue report page
 */
    public function catalogueReport(Request $request)
{
    $data['products'] = ProductCatalog::select(['id', 'name', 'model', 'product_brand_id', 'image_uploads'])
        ->with('brand:id,name')
        ->orderBy('name')
        ->get();

    $data['selectedProduct'] = null;
    $data['catalogueFiles'] = [];

    if ($request->filled('product_id')) {

        $data['selectedProduct'] = ProductCatalog::with('brand')
            ->find($request->product_id);

        if ($data['selectedProduct']) {

            // Since image_uploads is CASTED to array in the model
            $files = $data['selectedProduct']->image_uploads;

            if (is_array($files)) {
                $data['catalogueFiles'] = $files;
            }
        }
    }

    return view("Inventory::product-catalogs.catalogue-report", $data);
}


    /**
     * Download or view single catalogue file
     */
   public function viewCatalogueFile($productId, $fileIndex)
    {
        $product = ProductCatalog::findOrFail($productId);

        if (!$product->image_uploads) {
            abort(404, 'No catalogue files found');
        }

        // image_uploads ALWAYS returns array because of $casts
        $files = $product->image_uploads;

        if (!is_array($files) || !isset($files[$fileIndex])) {
            abort(404, 'File not found');
        }

        $filePath = $files[$fileIndex];
        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists($fullPath)) {
            abort(404, 'File not found on server');
        }

        $mimeType = mime_content_type($fullPath);
        $fileName = basename($filePath);

        $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

        if (in_array($mimeType, $imageTypes) || $mimeType === 'application/pdf') {
            return response()->file($fullPath);
        } else {
            return response()->download($fullPath, $fileName);
        }
    }

    public function productNameAutocomplete(Request $request, AutocompleteService $autocompleteService)
    {  
        //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
        $data = $autocompleteService->search(
            ProductCatalog::class,
            ['name','name'],
            $request->search,
            ['name', 'name'],
            30
        ); 
        return response()->json($data);
    }


     public function productModelAutocomplete(Request $request, AutocompleteService $autocompleteService)
    {  
        //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
        $data = $autocompleteService->search(
            ProductCatalog::class,
            ['model','model'],
            $request->search,
            ['model', 'model'],
            30
        ); 
        return response()->json($data);
    }
    

}