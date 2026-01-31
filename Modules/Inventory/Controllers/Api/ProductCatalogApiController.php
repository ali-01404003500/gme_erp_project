<?php

namespace Modules\Inventory\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Services\ProductCatalogService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Str;

class ProductCatalogApiController extends Controller
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
        $this->middleware('permited')->only(['create', 'store', 'edit', 'update', 'destroy']);

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


        return response()->json($data);
    }

    
    /**
     * Display a listing of the resource.
     */
    public function getAllProducts( Request $request)
    {
        $data['data'] =  ProductCatalog::query()->with('productType')->select(['id','name','model', 'product_type_id'  ])->get(); 
        $data['status'] = true;

        return response()->json($data);
    }


    /**
     * Get product price and discount for a customer
     */
    public function getPriceAndDiscount(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product_catalogs,id',
            'customer_id' => 'required|exists:customers,id'
        ]);

        $priceInfo = $this->service->getProductPriceAndDiscount(
            $request->product_id,
            $request->customer_id
        );

        return response()->json([
            'status' => true,
            'data' => $priceInfo
        ]);
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
        return response()->json(['message' => 'ProductCatalog created successfully.', 'data' => $productCatalog], 201);
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

        return response()->json($data);
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
            // 'product_code' => 'nullable',
        ]);
       
        $productCatalog = $this->service->update($productCatalog, $validate);

        return response()->json(['message' => 'ProductCatalog updated successfully.', 'data' => $productCatalog]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCatalog $productCatalog)
    {
        $this->service->delete($productCatalog);
        return response()->json(['message' => 'ProductCatalog deleted successfully.']);
    }

    public function countProduct(){
        return response()->json(["count" => $this->service->countProduct(),"current_month" =>$this->service->countProductCurrentMonth(), "previous_month" => $this->service->countProductPreviousMonth()]);
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

        while ($row = fgetcsv($file)) {
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
                continue;
            }

            try {
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
                        'is_warranty' => 'nullable|string|max:255',
                        'warranty_period' => 'nullable|string|max:255',
                        'warranty_period_input' => 'nullable|numeric',
                        'force_barcode_scan' => 'nullable|string|max:255',
                        'ecommerce_product' => 'nullable|string|max:255',
                        'description' => 'nullable|string|max:65535',
                        'product_code' => 'nullable',
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

                    ProductCatalog::create($validatedData);

                // }
            }
            catch (\Illuminate\Validation\ValidationException $e) {
                \Illuminate\Support\Facades\Log::error('Error importing product catalog: ' . $e->getMessage());
                return response()->json(['error' => 'Error importing product catalog: ' . $e->getMessage() . ' - ' . json_encode($e->errors())], 422);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        fclose($file);

        return response()->json(['message' => 'Product catalogs imported successfully.']);
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
     * Get product price and discount by product_id and customer_id.
     */
    public function getProductPriceAndDiscount(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product_catalogs,id',
            'customer_id' => 'nullable', // Assuming 'customers' table exists
        ]);

        $productId = $request->input('product_id');
        $customerId = $request->input('customer_id')??null;

        // Assuming ProductCatalogService has a method to handle this logic
        // You might need to implement this method in ProductCatalogService
        $data = $this->service->getProductPriceAndDiscount($productId, $customerId);

        return response()->json($data);
    }

}