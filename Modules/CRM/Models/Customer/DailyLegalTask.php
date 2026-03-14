<?php

namespace Modules\CRM\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;

class DailyLegalTask extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }  
}
