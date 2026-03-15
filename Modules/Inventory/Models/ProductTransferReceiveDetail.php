<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTransferReceiveDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function productCatalog()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }

    public function productTransferReceive()
    {
        return $this->belongsTo(ProductTransferReceive::class);
    }

    public function productTransferReceiveStockDetails()
    {
        return $this->hasMany(ProductTransferReceiveStockDetail::class, 'details_id');
    }
}
