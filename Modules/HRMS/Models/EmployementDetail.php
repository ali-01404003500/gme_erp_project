<?php

namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use App\Models\AccessControl\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;

class EmployementDetail extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function designation() {
        return $this->belongsTo(Designation::class);
    }
    public function supervisorName(){
        return $this->belongsTo(Employee::class, 'supervisor');
    }

}
