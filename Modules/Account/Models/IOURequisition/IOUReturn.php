<?php

namespace Modules\Account\Models\IOURequisition;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Transaction;

class IOUReturn extends BaseModel
{
    use HasFactory, SoftDeletes, AutoCreateUpdateAndHistory;
    
    protected $guarded = [];

    public function entry()
    {
        return $this->belongsTo(IOURequisitionEntry::class, 'entry_id');
    }


    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
