<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderDeliveryDetail extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function salesOrderDelivery() : BelongsTo
    {
        return $this->belongsTo(SalesOrderDelivery::class, 'sales_order_delivery_id');
    }

    public function salesOrderDeliveryStock() : HasMany
    {
        return $this->hasMany(SalesOrderDeliveryStock::class, 's_o_d_p_details_id');
    }

    public function product() : BelongsTo
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }

    public function salesProductOrderDetail(){
        return $this->salesOrderDelivery->salesOrder->salesOrderDetails->where('product_id', $this->product_id)->first();
    }
}
