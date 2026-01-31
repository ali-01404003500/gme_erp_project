<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;

class SalesReturnDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id', 'id');
    }
    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class, 'sales_return_id', 'id');
    }
    public function salesReturnStock()
    {
        return $this->hasMany(SalesReturnStock::class, 'sales_return_detail_id');
    }
     public function salesReturnStocks()
    {
        return $this->hasMany(SalesReturnStock::class, 'sales_return_detail_id');
    }
}
