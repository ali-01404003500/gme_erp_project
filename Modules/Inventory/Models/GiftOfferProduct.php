<?php

namespace Modules\Inventory\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftOfferProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    
    public function product()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id', 'id');
    }
}
