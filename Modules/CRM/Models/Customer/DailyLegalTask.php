<?php

namespace Modules\CRM\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;
use Modules\HRMS\Models\Employee;

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

    // Relationship: assign by
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'assign_to');
    }
}
