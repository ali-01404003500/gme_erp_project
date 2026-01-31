<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\Account\Models\Setup\BankBranch;

class AdvanceChequeEntryDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];
    public function advanceChequeEntry()
    {
        return $this->belongsTo(AdvanceChequeEntry::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function branch()
    {
        return $this->belongsTo(BankBranch::class);
    }

    public function emiEntryDetail()
    {
        return $this->belongsTo(EMIEntryDetail::class, 'emi_entry_details_id');
    }

    public function chcqueVerification()
    {
        return $this->hasOne(ChequeVerification::class, 'source_id')->where('source_type', AdvanceChequeEntryDetail::class);
    }
}
