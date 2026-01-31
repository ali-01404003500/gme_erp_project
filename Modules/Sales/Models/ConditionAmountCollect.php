<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Modules\CRM\Models\Customer\Customer;

class ConditionAmountCollect extends BaseModel
{
    use HasFactory, SoftDeletes, AutoCreateUpdateAndHistory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function shipmentVerify()
    {
        return $this->belongsTo(ShipmentVerify::class);
    }

    public function transactions()
    {
        return $this->morphMany(\Modules\Account\Models\Transaction::class, 'transactionable');
    }
}
