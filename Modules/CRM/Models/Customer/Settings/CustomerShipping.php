<?php

namespace Modules\CRM\Models\Customer\Settings;

use App\Models\BaseModel;
use App\Traits\AutoHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CRM\Models\Customer\Customer;

class CustomerShipping extends BaseModel
{
    use HasFactory;
    use AutoHistory;
    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}
