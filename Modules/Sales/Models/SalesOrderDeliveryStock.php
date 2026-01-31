<?php

namespace Modules\Sales\Models;

use Modules\Inventory\Models\ProductCatalog;
use App\Models\StockModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderDeliveryStock extends StockModel
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function salesOrderDeliveryDetail() : BelongsTo
    {
        return $this->belongsTo(SalesOrderDeliveryDetail::class, 's_o_d_p_details_id');
    }

    function getParentIdAttribute(){
        return $this->salesOrderDeliveryDetail->sales_order_delivery_id;
    }

    function productCatalog() : BelongsTo
    {
        return $this->belongsTo(ProductCatalog::class, 'product_catalog_id');
    }
}
