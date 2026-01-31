<?php

namespace Modules\Inventory\Services;


use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\ProductCatalogBarcode;
use App\Traits\S3FileHandler;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\CustomerSetting;
use Modules\Inventory\Models\Product;

class ProductCatalogService
{
    use S3FileHandler;
    
    public function getAll(int $limit = 20) {
        return ProductCatalog::query()
        ->searchByFields(['name', 'model', 'product_brand_id'])
        // ->branchOnly()
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        // dd($data);
        // $image_uploads = [];
        // foreach ($data['image_uploads']??[] as $key => $image) {
        //     $image_uploads[$key] = $this->uploadFile($image, 'product_catalog');
        // }
        // $data['image_uploads'] = json_encode($image_uploads);
        /**catalog_file
            price_list_file */
        // if (isset($data['catalog_file'])) {
        //     $data['catalog_file'] = $this->uploadFile($data['catalog_file'], 'product_catalog');
        // }
        // if (isset($data['price_list_file'])) {
        //     $data['price_list_file'] = $this->uploadFile($data['price_list_file'], 'product_catalog');
        // }
        // if (isset($data['profile_image_upload'])) {
        //     $data['profile_image_upload'] = $this->uploadFile($data['profile_image_upload'], 'product_catalog');
        // }

        $productCatalog = ProductCatalog::create($data);
        return $productCatalog;
    }

    public function update(ProductCatalog $productCatalog, array $data)
    {
        // $image_uploads = [];
        // foreach ($data['image_uploads']??[] as $key => $image) {
        //     $image_uploads[$key] = $this->uploadFile($image, 'product_catalog');
        // }
        // $data['image_uploads'] = json_encode($image_uploads);
        /**catalog_file
            price_list_file */
        // if (isset($data['catalog_file'])) {
        //     $data['catalog_file'] = $this->uploadFile($data['catalog_file'], 'product_catalog');
        // }
        // if (isset($data['price_list_file'])) {
        //     $data['price_list_file'] = $this->uploadFile($data['price_list_file'], 'product_catalog');
        // }
    
        if($productCatalog->product()->first()){
            $productCatalog->product()->first()->update(['product_tag_id'=> $data['product_tag_id']]);
        }
            // if (isset($data['profile_image_upload'])) {
            
            //     $data['profile_image_upload'] = $this->uploadFile($data['profile_image_upload'], 'product_catalog');
            // }
            
        $productCatalog->update($data);
        return $productCatalog;
    }

    public function delete(ProductCatalog $productCatalog)
    {
        DB::beginTransaction();
        // Delete associated barcodes
        $productCatalog->productSetting()->delete();
        $productCatalog->delete();
        DB::commit();
    }

    public function show($id)
    {
        return ProductCatalog::findOrFail($id);
    }

    public function countProduct() {
        return ProductCatalog::count();
    }

    public function countProductCurrentMonth() {
        return ProductCatalog::query()->whereMonth('created_at', Carbon::now()->month)->count();
    }

    
    public function countProductPreviousMonth() {
        return ProductCatalog::query()->whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
    }

    public function getProductPriceAndDiscount(int $productId, ?int $customerId = null)
    {
        $customerSetting = CustomerSetting::with(["customerSettingBrokers", "customerSettingDiscounts", "customerSettingFixedDiscounts", "customerSettingSelfCommissions"])->where('customer_id', $customerId)->first()??null;
        $productSetting = Product::where('product_catalog_id', $productId)->first();
        $percentage = null;
        $productPrice = null;
        $discountRange = null;
       
        // dd($productSetting->productCatalog->mrp);
        $basePrice = ProductCatalog::find($productId)->mrp;
        $discountAmount = 0;
        $discountType = null;
        $discountedPrice = $basePrice;
        
        // Check for customer-specific discounts

        if ($productSetting && $customerSetting) {
            if ($productSetting->discount_type == "Percentage") {
                if ($customerSetting->discount_type == 1 || $customerSetting->discount_type == 3) {// percentage
                    $percentage = $customerSetting?->customerSettingDiscounts?->where("percentage_type", $productSetting?->product_tag_id)->first();
                    $discountAmount = ($basePrice * $percentage?->percentage??0) / 100;
                    $discountType = 'percentage';
                    $discountedPrice = $basePrice - $discountAmount;
                }
            } else if ($productSetting->discount_type == "Fixed") {
                if ($customerSetting->discount_type == 2 || $customerSetting->discount_type == 3) {// fixed
                    $productPrice = $customerSetting->customerSettingFixedDiscounts->where('product_id', $productId)->first();
                    if ($productPrice) {
                        $discountAmount = $basePrice - $productPrice->sales_amounts;
                        $discountType = 'fixed';
                        $discountedPrice = $productPrice->sales_amounts;
                    } else {
                        $discountType = 'range';
                        $discountRange = ['min' => $productSetting->min_discount, 'max' => $productSetting->max_discount];
                    }
                }
            }
        }

        return [
            'base_price' => $basePrice,
            'discounted_price' => $discountedPrice,
            'discount_amount' => $discountAmount,
            'discount_type' => $discountType,
            'discount_range' => $discountRange,
            'percentage' => $percentage?->percentage,
            'product_price' => $productPrice?->sales_amounts,
        ];
        
    }

}
