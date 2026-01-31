<?php

namespace Modules\Inventory\Controllers\Product\Settings;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Inventory\Services\Product\Settings\BrandService;
use Modules\Purchase\Models\Supplier;

class BrandController extends Controller
{

    /**
     * Service variable
     *
     * @var BrandService
     */
    private $service; 
    function __construct(BrandService $service)
    {
        $this->service = $service;
        $this->middleware('permited');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['brands'] = $this->service->getAll();
        $data['suppliers'] = Supplier::query()->where('status', 1)->get();

        return view("Inventory::product.settings.brand.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,NULL,id,deleted_at,NULL',
            'code' => 'required|string|max:255|unique:brands,code,NULL,id,deleted_at,NULL',
            'supplier_id' => 'required',
        ]);
        $this->service->create($validate);
        return redirect()->route('inv.brands.index')->with('success', 'Brand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['brand'] = $this->service->show($id);

        return view("brands.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        $data['brand'] = $brand;
        $data['suppliers'] = Supplier::query()->where('status', 1)->get();
        return view("brands.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'supplier_id' => 'required',
        ]);
        $this->service->update($brand, $validate);

        return redirect()->route('inv.brands.index')->with('success', 'Brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $this->service->delete($brand);
        return redirect()->route('inv.brands.index')->with('success', 'Brand deleted successfully.');
    }
    
    public function getProductCatalogs($id) {
        return $this->service->getProductCatalogs($id);
    }
}
