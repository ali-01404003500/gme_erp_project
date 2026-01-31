<?php

namespace Modules\CRM\Models\Customer;

use App\Models\BaseModel;
use App\Models\Customer\Settings\PercentageType;
use App\Models\GeoLocation;
use Modules\Inventory\Models\Settings\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrokerCommission extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function PercentageType(){
        return $this->belongsTo(Tag::class,"percentage_type");
    }

   

}
