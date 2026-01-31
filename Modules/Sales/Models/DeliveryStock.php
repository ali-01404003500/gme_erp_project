<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use App\Models\StockModel;
use Modules\Inventory\Models\ProductCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Inventory\Services\StockService;

class DeliveryStock extends StockModel
{
    use HasFactory;
    protected $guarded = [];

    public function productCatalog() : BelongsTo {
        return $this->belongsTo(ProductCatalog::class, 'product_catalog_id');
    }

    function deliveryDetail() : BelongsTo
    {
        return $this->belongsTo(DeliveryDetail::class, 'delivery_detail_id');
    }
    

    public function getParentIdAttribute()
    {
        return $this->delivery_detail_id;
    }

    
}
