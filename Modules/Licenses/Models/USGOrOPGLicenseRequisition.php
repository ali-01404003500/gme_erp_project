<?php

namespace Modules\Licenses\Models;


use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;

class USGOrOPGLicenseRequisition extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;
    protected $guarded = [];

    public function phones()
    {
        return $this->hasMany(USGOrOPGLicenseRequisitionPhone::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function dongles(){
        return $this->belongsTo(DongleOrSerialEntry::class, 'dongle_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }

}
