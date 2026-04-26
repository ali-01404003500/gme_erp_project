<?php

namespace Modules\Inventory\Services;


use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\ProductBarcode;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Settings\Tag;
use Modules\Inventory\Models\Settings\Unit;
use App\Traits\S3FileHandler;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductService
{
    use S3FileHandler;
    
    public function getAll(int $limit = 20) {
        return Product::query()->paginate($limit);
    }

    public function create()
    {
     
         
    }
    
    public function store(array $data, array $barcodes=[])
    {
        if(isset($data['image_upload'])) {
            $data['image_upload']= $this->uploadFile($data['image_upload'], 'products');
        }
        if(isset($data['catalog_file'])) {
            $data['catalog_file']= $this->uploadFile($data['catalog_file'], 'products');
        }
        if(isset($data['price_list_file'])) {
            $data['price_list_file']= $this->uploadFile($data['price_list_file'], 'products');
        }

        $result['products'] = Product::create($data);
        $result['products']->productCatalog()->update(['product_tag_id' => $data['product_tag_id']]);
        // $result['barcodes'] = [];
        // foreach ($barcodes['barcodes'] as $key => $value) {
        //     $result['barcodes'][] = ProductBarcode::create([
        //         'product_id' => $result['products']->id,
        //         'barcode' => $value,
        //     ]);
        // }

        return $result;
    }

    public function update(Product $product, array $data)
    {
        if(isset($data['image_upload'])) {
            $data['image_upload']= $this->uploadFile($data['image_upload'], 'products');
        }
        if(isset($data['catalog_file'])) {
            $data['catalog_file']= $this->uploadFile($data['catalog_file'], 'products');
        }
        if(isset($data['price_list_file'])) {
            $data['price_list_file']= $this->uploadFile($data['price_list_file'], 'products');
        }
        // dd($data);
        $product->update($data);
        $product->productCatalog()->update(['product_tag_id' => $data['product_tag_id']]);
        return $product;
    }

    public function delete(Product $product)
    {
        $product->delete();
    }

    public function show($id)
    {
        return Product::query()->with(["productType", "productCatalog", "tag", "brand", "unit"])->findOrFail($id);
    }

    public function search(string $q){
        return Product::query()->where('name', 'like', '%'.$q.'%')->get();
    }

    

    
    // public function countProductPreviousMonth() {
    //     $today = Carbon::now();
    //     $previousMonth = $today->subMonth(2);
    //     $previousMonthEnd = $previousMonth->copy()->endOfMonth();
    //     return Product::query()->whereBetween('created_at', [$previousMonth, $previousMonthEnd])->count();
    // }
}
