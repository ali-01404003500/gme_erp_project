<?php

namespace Modules\Account\Models;

use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;
use Modules\Sales\Models\SalesOrder;

class EMIEntry extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    
    protected $guarded = [];

    public function emiDetails()
    {
        return $this->hasMany(EMIEntryDetail::class, 'emi_entry_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

     public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

}
