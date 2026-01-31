<?php

namespace Modules\HRMS\Models\Kpi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;

class KpiTemplateAssignEmployee extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes; 
    
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function kpiTemplate()
    {
        return $this->belongsTo(KpiTemplate::class);
    }

    public function details()
    {
        return $this->hasMany(KpiTemplateAssignEmployeeDetail::class);
    }
}
