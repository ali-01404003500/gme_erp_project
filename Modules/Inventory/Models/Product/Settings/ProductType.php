<?php

namespace Modules\Inventory\Models\Product\Settings;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductCatalog;

class ProductType extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    public $deletePrevent = ['productCatalogs', 'products'];

    protected $guarded = [];

    public function productCatalogs(){
        return $this->hasMany(ProductCatalog::class);
    }

    
    public function products(){
        return $this->hasMany(Product::class, 'product_type_id');
    }
    
    // public function productTransferDetails(){
    //     return $this->hasMany(ProductTransferDetail::class, 'product_type_id');
    // }
    
    // public function productTransferRequestDetails(){
    //     return $this->hasMany(ProductTransferRequestDetail::class, 'product_type_id');
    // }
}
