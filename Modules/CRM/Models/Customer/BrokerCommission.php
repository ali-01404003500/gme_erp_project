<?php

namespace Modules\CRM\Models\Customer;

use App\Models\BaseModel;
use App\Models\Customer\Settings\PercentageType;
use App\Models\GeoLocation;
use Modules\Inventory\Models\Settings\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Models\ProductCatalog;

class BrokerCommission extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function PercentageType(){
        return $this->belongsTo(Tag::class,"percentage_type");
    }


    public function FixedType(){
        return $this->belongsTo(Tag::class,"fixed_type");
    }

    public function product(){
        return $this->belongsTo(ProductCatalog::class,"fixed_type","id");
    }

    



   

}
