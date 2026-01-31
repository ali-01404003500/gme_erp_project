<?php

namespace Modules\Services\Models;

use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Employee;
use Modules\Services\Models\Settings\ServiceType;

class EngineerAssign extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;    
    protected $guarded = [];

    public function engineers()
    {
        return $this->belongsToMany(Employee::class, 'engineer_assigns_engineers', 'engineer_assign_id', 'engineer_id')
                    ->withTimestamps();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function serviceType(){
        return $this->belongsTo(ServiceType::class, 'service_type', 'id');

    }
}
