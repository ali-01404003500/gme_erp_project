<?php

namespace Modules\CRM\Models\Customer\Settings;

use App\Models\BaseModel;
use Modules\Sales\Models\Quotation;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;

class CustomerType extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    public $deletePrevent = ['customers','quotations'];



    public function customers()
    {
        return $this->hasMany(Customer::class,'customer_type');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class,'customer_type');
    }

}
