<?php

namespace Modules\Inventory\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClearanceOfferRange extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function offerDetails()
    {
        return $this->belongsTo(OfferDetail::class, 'offer_detail_id');
    }

}
