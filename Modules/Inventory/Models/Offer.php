<?php

namespace Modules\Inventory\Models;


use App\Models\BaseModel;
use App\Traits\AutoCreatedUpdated;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public function offerDetails() {
        return $this->hasMany(OfferDetail::class, 'offer_id', 'id');
    }

    /**
     * Polymorphic many-to-many relationship with offerable models
     */
    public function offerables(): MorphToMany
    {
        return $this->morphToMany(
            related: '*',
            name: 'offerable',
            table: 'offerables',
            foreignPivotKey: 'offer_id',
            relatedPivotKey: 'offerable_id',
            relation: 'offerables'
        );
    }

    /**
     * Relationship to SalesOrders that have this offer
     */
    public function salesOrders()
    {
        return $this->morphedByMany(
            related: \Modules\Sales\Models\SalesOrder::class,
            name: 'offerable',
            table: 'offerables',
            foreignPivotKey: 'offer_id',
            relatedPivotKey: 'offerable_id'
        );
    }

    /**
     * Relationship to Services that have this offer
     */
    public function services()
    {
        return $this->morphedByMany(
            related: \Modules\Services\Models\Service::class,
            name: 'offerable',
            table: 'offerables',
            foreignPivotKey: 'offer_id',
            relatedPivotKey: 'offerable_id'
        );
    }
}
// General 