<?php

namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeSalary extends BaseModel
{
    use HasFactory;
    
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

}
