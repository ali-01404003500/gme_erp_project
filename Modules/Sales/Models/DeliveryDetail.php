<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDetail extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function product(){
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }

    public function deliveryStocks(){
        return $this->hasMany(DeliveryStock::class, 'delivery_detail_id');
    }

    public function delivery(){
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }

    public function productCatalog(){
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }
}
