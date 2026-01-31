<?php

namespace Modules\HRMS\Models\Settings;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\LeaveApplication;

class LeaveType extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    public $deletePrevent = ['leaveApplications'];


    protected $guarded = [];

    
    public function leaveApplications() {
        return $this->hasMany(LeaveApplication::class, 'leave_type_id');
    }

}
