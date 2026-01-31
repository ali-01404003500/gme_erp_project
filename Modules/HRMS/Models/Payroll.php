<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Settings\Department;

class Payroll extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    
    protected $guarded = [];

    public function salaryGenerates()
    {
        return $this->hasMany(SalaryGenerate::class, 'payroll_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
