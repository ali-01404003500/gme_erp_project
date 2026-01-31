<?php

namespace Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Illuminate\Http\Request;
use Modules\Account\Controllers\ProductController;
use Modules\HRMS\Models\Employee;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Settings\Tag;

class ProductPriceListController extends Controller
{

    // /**
    //  * Service variable
    //  *
    //  * @var ProductPriceListControllerService
    //  */
    // private $service; 
    // function __construct(ProductPriceListControllerService $service)
    // {
    //     $this->service = $service;
    // }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $query = ProductCatalog::active()->with(['productType', 'brand', 'unit']);

        if ($request->product_brand_ids) {
            $query->whereIn('product_brand_id', $request->product_brand_ids);
        }

        if ($request->tags) {
            $query->whereIn('product_tag_id', $request->tags);
        }

        $data['products'] = $query->get();

        $data['productCatalogs'] = ProductCatalog::select('id', 'name')->get();


        $data['employees'] = Employee::select('id', 'full_name')->get();
        $data['company_info'] = CompanyInfo::first();
        $data['productBrands'] = Brand::all();
        $data['tags'] = Tag::select('id', 'name')->get();


        return view("Inventory::product-price-list.index", $data);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     return view('productPriceListControllers.create');
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     $validate = $request->validate([
    //         //validate rules
    //     ]);
    //     $this->service->store($validate);
    //     return redirect()->route('productPriceListControllers.index')->with('success', 'ProductPriceListController created successfully.');
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show( $id)
    // {
    //     $data['productPriceListController'] = $this->service->show($id);

    //     return view("productPriceListControllers.show", $data);
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(ProductPriceListController $productPriceListController)
    // {
    //     $data['productPriceListController'] = $productPriceListController;
    //     //
    //     return view("productPriceListControllers.edit", $data);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, ProductPriceListController $productPriceListController)
    // {
    //     $validate = $request->validate([
    //         //validate rules
    //     ]);
    //     $this->service->update($productPriceListController, $validate);

    //     return redirect()->route('productPriceListControllers.index')->with('success', 'ProductPriceListController updated successfully.');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(ProductPriceListController $productPriceListController)
    // {
    //     $this->service->delete($productPriceListController);
    //     return redirect()->route('productPriceListControllers.index')->with('success', 'ProductPriceListController deleted successfully.');
    // }
}
