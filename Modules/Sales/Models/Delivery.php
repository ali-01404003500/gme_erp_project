<?php

namespace Modules\Sales\Models;


use App\Models\BaseModel;
use App\Models\KeepSign;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Transaction;

class Delivery extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    public function source()
    {
        return $this->morphTo('source', 'source_type', 'source_id');
    }

    public function deliveryDetails()
    {
        return $this->hasMany(DeliveryDetail::class, 'delivery_id');
    }
    public function arrangedBy()
    {
        return $this->belongsTo(\Modules\HRMS\Models\Employee::class, 'arranged_by');
    }

    public function checkedBy()
    {
        return $this->belongsTo(\Modules\HRMS\Models\Employee::class, 'checked_by');
    }

    /**
     * The account transactions that belong to the SalesOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
     public function transactions()
    {
        return $this->morphMany(Transaction::class , 'transactionable');
    }

    public function signature()
    {
        return $this->morphOne(KeepSign::class, 'keep_signatureable');
    }


    
}
