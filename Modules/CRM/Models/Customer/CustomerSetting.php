<?php

namespace Modules\CRM\Models\Customer;

use App\Models\BaseModel;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerSetting extends BaseModel
{
    use AutoCreatedUpdated;
    use HasFactory;
    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function customerSettingBrokers(){
        return $this->hasMany(CustomerSettingBroker::class);
    }
    public function customerSettingDiscounts(){
        return $this->hasMany(CustomerSettingDiscount::class);
    }
    public function customerSettingFixedDiscounts(){
        return $this->hasMany(CustomerSettingFixedDiscount::class);
    }
    public function customerSettingSelfCommissions(){
        return $this->hasMany(CustomerSettingSelfCommission::class);
    }

}
