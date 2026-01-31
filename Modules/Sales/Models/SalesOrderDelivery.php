<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderDelivery extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    function salesOrder() :  BelongsTo 
    {
        return $this->belongsTo(SalesOrder::class, "sales_order_id");
    }

    function salesOrderDeliveryDetails() : HasMany {
        
        return $this->hasMany(SalesOrderDeliveryDetail::class, "sales_order_delivery_id");
    }
}
