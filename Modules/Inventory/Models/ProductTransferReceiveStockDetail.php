<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTransferReceiveStockDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function productTransferReceiveDetail()
    {
        return $this->belongsTo(ProductTransferReceiveDetail::class, 'details_id');
    }

    public function productCatalog()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }
}
