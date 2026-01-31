<?php

namespace Modules\HRMS\Models;

use App\Models\AccessControl\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;

class JobTemplate extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation(){
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
