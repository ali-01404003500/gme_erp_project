<?php

namespace Modules\Inventory\Controllers\Product\Settings;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Services\Product\Settings\ProductTypeService;

class ProductTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var ProductTypeService
     */
    private $service; 
    function __construct(ProductTypeService $service)
    {
        $this->service = $service;
        $this->middleware('permited')->except('productCatalogs');

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['productTypes'] = $this->service->getAll();

        return view("Inventory::product.settings.product-type.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
         'name' => 'required|string|max:255|unique:product_types,name,Null,id,deleted_at,NULL',
         'code' => 'required|string|max:255|unique:product_types,code,Null,id,deleted_at,NULL',
        ]);
        $this->service->create($validate);
        return redirect()->route('inv.product-types.index')->with('success', 'ProductType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['productType'] = $this->service->show($id);

        return view("productTypes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductType $productType)
    {
        $data['productType'] = $productType;
        //
        return view("productTypes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductType $productType)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
        ]);
        $this->service->update($productType, $validate);

        return redirect()->route('inv.product-types.index')->with('success', 'Product Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductType $productType)
    {
        $this->service->delete($productType);
        return redirect()->route('inv.product-types.index')->with('success', 'Product Type deleted successfully.');
    }

    public function productCatalogs($id){
        $productCatalogs = $this->service->productCatalogs($id);
        return response()->json($productCatalogs->toArray());
    }
}
