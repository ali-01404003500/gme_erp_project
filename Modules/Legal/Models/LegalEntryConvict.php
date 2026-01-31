<?php

namespace Modules\Legal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\CRM\Models\Customer\Customer;

class LegalEntryConvict extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function legalEntry()
    {
        return $this->belongsTo(LegalEntry::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


}
