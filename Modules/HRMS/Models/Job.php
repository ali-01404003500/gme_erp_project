<?php

namespace Modules\HRMS\Models;

use App\Models\AccessControl\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;

class Job extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];


    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation(){
        return $this->belongsTo(Designation::class, 'designation_id');
    }
}
