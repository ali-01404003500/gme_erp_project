<?php

namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Settings\Department;

class SalaryGenerate extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function salaryGeneratePayments()
    {
        return $this->hasMany(SalaryGeneratePayment::class, 'salary_generate_id');
    }

    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }
}
