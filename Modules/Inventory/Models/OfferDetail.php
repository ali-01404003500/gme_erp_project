<?php

namespace Modules\Inventory\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'id');
    }


    public function giftSalesProducts()
    {
        return $this->hasMany(GiftSalesProduct::class, 'offer_detail_id', 'id');
    }


    public function giftOfferProducts()
    {
        return $this->hasMany(GiftOfferProduct::class, 'offer_detail_id', 'id');
    }

    public function discountSalesProducts()
    {
        return $this->hasMany(DiscountSalesProduct::class, 'offer_detail_id', 'id');
    }

    public function offerDiscounts()
    {
        return $this->hasMany(OfferDiscount::class, 'offer_detail_id', 'id');
    }

    public function clearanceOfferRanges()
    {
        return $this->hasMany(ClearanceOfferRange::class, 'offer_detail_id', 'id');
    }
}
