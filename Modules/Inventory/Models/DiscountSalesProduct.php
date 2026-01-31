<?php

namespace Modules\Inventory\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountSalesProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function offerDetail()
    {
        return $this->belongsTo(OfferDetail::class, 'offer_detail_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class, 'sales_product', 'id');
    }


}
