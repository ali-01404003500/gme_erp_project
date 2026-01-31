<?php

namespace Modules\Legal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Purchase\Models\Vendor;

class LegalBillEntry extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;  
    protected $guarded = [];
    protected $casts = [
                'attachment' => 'array'
            ];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function legalEntry()
    {
        return $this->belongsTo(LegalEntry::class, 'legal_entry_id');
    }
}
