<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;

class AdvanceChequeEntry extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

 

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reference()
    {
        return $this->belongsTo(EMIEntry::class);
    }
    public function details()
    {
        return $this->hasMany(AdvanceChequeEntryDetail::class, 'advance_cheque_entry_id');
    }

}
