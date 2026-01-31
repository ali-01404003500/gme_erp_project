<?php

namespace Modules\Sales\Models;


use App\Models\BaseModel;
use App\Models\KeepSign;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Payment;
use Modules\CRM\Models\Customer\Customer;

class SalesRequisition extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;
    protected $guarded = [];

    public function salesRequisitionDetails(){
        return $this->hasMany(SalesRequisitionDetail::class, 'sales_requisition_id');
    }
    public function details(){
        return $this->salesRequisitionDetails();
    }
    

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function requisitionBy(){
        return $this->belongsTo(User::class, 'created_by');
    }

    // public function shipment(){
    //     return $this->hasOne(SalesRequisitionShipment::class, 'sales_requisition_id');
    // }
    public function shipment(){
        return $this->morphOne(ShipmentConditionInfo::class, 'for', 'for_type', 'for_id');
    }
    
    public function delivery(){
        return $this->morphOne(Delivery::class, 'source', 'source_type', 'source_id');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function signature()
    {
        return $this->morphOne(KeepSign::class, 'keep_signatureable');
    }

}
