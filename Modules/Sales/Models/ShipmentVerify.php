<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use App\Models\MultiFile;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;

class ShipmentVerify extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;


    protected $guarded = [];

    protected $casts = [
        'files' => 'array', // Automatically handle JSON encoding/decoding
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    public function source()
    {
        return $this->morphTo('source', 'source_type', 'source_id');
    }

    public function transactions()
    {
        return $this->morphMany(\Modules\Account\Models\Transaction::class, 'transactionable');
    }
}
