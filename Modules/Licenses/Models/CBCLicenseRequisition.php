<?php

namespace Modules\Licenses\Models;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;

class CBCLicenseRequisition extends  BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;
    protected $guarded = [];

    public function phones()
    {
        return $this->hasMany(CBCLicenseRequisitionPhone::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function dongles(){
        return $this->belongsTo(DongleOrSerialEntry::class, 'dongle_id');
    }
}
