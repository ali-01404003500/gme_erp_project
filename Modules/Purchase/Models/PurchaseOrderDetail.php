<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\ProductCatalog;

class PurchaseOrderDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'purchase_order_id', 'product_id', 'product_model',
        'product_description', 'hs_code', 'quantity', 'price', 'amount',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class);
    }

}
