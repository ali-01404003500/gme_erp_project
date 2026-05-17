<?php

namespace Modules\Services\Models;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;

class ServiceToken extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class);
    }
    public function emergencyNotes()
    {
        return $this->hasMany(EmergencyNote::class);
    }

    public function engineerAssign(){
        return $this->hasOne(EngineerAssign::class, 'service_token_id');
    }

    public function serviceMyTask(){
        return $this->hasOne(ServiceMyTask::class, 'service_token_id');
    }
    
    public function scopeLatest($query)
    {
        return $query->orderBy($this->getTable().'.created_at', 'desc');
    }

}
