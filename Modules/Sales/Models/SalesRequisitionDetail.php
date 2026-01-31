<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesRequisitionDetail extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function product(){
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }

}
