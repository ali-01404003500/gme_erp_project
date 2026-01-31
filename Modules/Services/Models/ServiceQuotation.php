<?php

namespace Modules\Services\Models;

use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;

class ServiceQuotation extends BaseModel
{
    
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;
    protected $guarded = [];

    public function quotationDetails()
    {
        return $this->hasMany(ServiceQuotationDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function service(){
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(){
        return $this->belongsTo(User::class, 'approved_by');
    }

  
}
