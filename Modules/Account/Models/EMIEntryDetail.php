<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
use App\Models\KeepSign;

class EMIEntryDetail extends BaseModel
{
    use HasFactory;
    protected $casts = [
        'receipt_no' => 'array',
    ];

    
    
    protected $guarded = [];

     public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function emiEntry()
    {
        return $this->belongsTo(EMIEntry::class, 'emi_entry_id');
    }
     public function signature(){
        return $this->morphOne(KeepSign::class, 'keep_signatureable');
    }

    public function advanceChequeEntryDetail(){
        return $this->hasOne(AdvanceChequeEntryDetail::class,'emi_entry_details_id');
    }
}


