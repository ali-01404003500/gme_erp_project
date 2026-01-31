<?php

namespace Modules\Account\Controllers;

use Illuminate\Http\Request;
use App\Traits\CheckPermission;
use Modules\Account\Models\Unit;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Product;
use Modules\Account\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Modules\Account\Services\ProductStockService;

class ProductController extends Controller
{
    


    public $stockService;








    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {

        $this->stockService       = new ProductStockService();
    }





    public function index()
    {
        
        

        $products   = Product::userCompanies()->with('category', 'unit')->whereIn('product_type', ['0', 'account_prod'])->userLog()->latest()->get();

        return view('Account::product.products.index', compact('products'));
    }




    public function create()
    {
        

        $categories = Category::orderBy('name')->pluck('name', 'id');
        $units      = Unit::orderBy('name')->pluck('name', 'id');

        return view('Account::product.products.create', compact('categories', 'units'));
    }



    // : RedirectResponse
    public function store(Request $request)
    {
        

        $request->validate([
            'name'          => 'required',
            'category_id'   => 'required',
            'unit_id'       => 'required'
        ]);

        DB::beginTransaction();


        $product = Product::create([

            'name'              => $request->name,
            'category_id'       => $request->category_id,
            'unit_id'           => $request->unit_id,
            'purchase_price'    => $request->purchase_price ?? 0,
            'product_type'      => 'account_prod',
            'selling_price'     => $request->selling_price ?? 0,
            'opening_quantity'  => $request->opening_quantity ?? 0,
            'current_stock'     => 0.00,
            'description'       => $request->description,
        ]);

        $product->update([

            'product_code' => 'prod-' . $product->id . '-' . time()
        ]);



        // $this->stockService->storeRequisitionStock($product->id, ('product-10000' . $product->id), "Account Product Opening", date('Y-m-d'), 0, $product->opening_quantity, $product->id, 0, $product->purchase_price, $request->company_id, $request->factory_id);


        // $this->stockService->updateRmStock($product->id, $request->company_id, $request->factory_id, $request->req_purchase_receive_date);


        DB::commit();

        return redirect()->route('account.products.index')->with('success', 'Product Create Successful');
    }





    public function edit(Product $product)
    {
        

        $categories = Category::orderBy('name')->pluck('name', 'id');
        $units      = Unit::orderBy('name')->pluck('name', 'id');

        return view('Account::product.products.edit', compact('product', 'categories', 'units'));
    }





    public function update(Request $request, Product $product): RedirectResponse
    {
        

        $request->validate([
            'name'          => 'required',
            'category_id'   => 'required',
            'unit_id'       => 'required'
        ]);

        $product->update(
            [
                'name'              => $request->name,
                'category_id'       => $request->category_id,
                'unit_id'           => $request->unit_id,
                'purchase_price'    => $request->purchase_price ?? 0,
                'selling_price'     => $request->selling_price ?? 0,
                'opening_quantity'  => $request->opening_quantity ?? 0,
                'product_type'      => 'account_prod',
                'current_stock'     => 0.00,
                'description'       => $request->description,
            ]
        );

        return redirect()->route('account.products.index')->with('success', 'Product Update Successful');
    }






    public function destroy($id)
    {
        

        try {
            Product::destroy($id);

            return redirect()->route('account.products.index')->with('success', 'Product Successfully Deleted!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
}
