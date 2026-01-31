<?php

namespace Modules\Sales\Models;

use Modules\Inventory\Models\ProductCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_order_id',
        'product_id',
        'quantity',
        'price',
        'unit_discount',
        'total_discount',
        'amount',
        'stock_details',
        'is_offers_product',
        'discount_type',
    ];
    
    protected $with = ['product'];
    protected $casts = [
        'stock_details' => 'array',
    ];

    public function setIsOffersProductAttribute($value)
    {
        if ($value === false || $value === null || $value === '') {
            $this->attributes['is_offers_product'] = 0;
        } elseif ($value == true && is_numeric($value) && $value >= 2) {
            // If numeric value is 2 or more, store the actual number
            $this->attributes['is_offers_product'] = $value;
        } elseif ($value == true) {
            // If it's true (but not a number >= 2), store 1
            $this->attributes['is_offers_product'] = 1;
        } else {
            // For any other case, store as is
            $this->attributes['is_offers_product'] = $value;
        }
    }

    function product()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id', 'id');
    }

    function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'id');
    }
    
       
}
