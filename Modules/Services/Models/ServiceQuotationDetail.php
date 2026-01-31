<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;

class ServiceQuotationDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];
     public function quotation()
    {
        return $this->belongsTo(ServiceQuotation::class, 'service_quotation_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }
}
