<?php

namespace Modules\CMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\AdvanceChequeEntryDetail;
use Modules\CRM\Models\Customer\Customer;

class ApplicationEntry extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function handoverBy()
    {
        return $this->belongsTo(User::class, 'handover_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
    public function deniedBy()
    {
        return $this->belongsTo(User::class, 'denied_by');
    }
    public function advanceChequeEntryDetail()
    {
        return $this->belongsTo(AdvanceChequeEntryDetail::class, 'advance_cheque_entry_detail_id');
    }
}
