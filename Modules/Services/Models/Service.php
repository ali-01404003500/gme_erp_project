<?php

namespace Modules\Services\Models;


use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Services\Models\Settings\ServiceType;

class Service extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];
    

    
    public function serviceTokens()
    {
        return $this->hasMany(ServiceToken::class);
    }

    public function serviceTypes()
    {
        return $this->hasMany(ServiceType::class, "service_id");
    }

    public function product()
    {
        return $this->hasMany(ProductCatalog::class, "product_id");
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function emergencyNotes()
    {
        return $this->hasMany(EmergencyNote::class, "service_id");
    }
    

    /**
     * Polymorphic many-to-many relationship with offers
     */
    public function offers()
    {
        return $this->morphToMany(
            related: \Modules\Inventory\Models\Offer::class,
            name: 'offerable',
            table: 'offerables',
            foreignPivotKey: 'offerable_id',
            relatedPivotKey: 'offer_id'
        );
    }

}
