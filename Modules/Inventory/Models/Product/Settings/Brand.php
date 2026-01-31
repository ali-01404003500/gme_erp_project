<?php

namespace Modules\Inventory\Models\Product\Settings;

use App\Models\BaseModel;

use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\Supplier;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Models\ProductCatalog;

class Brand extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    public $deletePrevent = ['productCatalogs','purchaseOrders'];
    protected $guarded = [];

    public function productCatalogs(){
        return $this->hasMany(ProductCatalog::class,'product_brand_id');
    }

    public function productCatalog(){
        return $this->hasMany(ProductCatalog::class,'product_brand_id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function manufacturer(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
    public function purchaseOrders(){
        return $this->hasMany(PurchaseOrder::class,'search_by_brand_id');
    }
}
