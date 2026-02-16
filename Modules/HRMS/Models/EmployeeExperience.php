<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeExperience extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory; 
    
    protected $fillable = [
        'employee_id',
        'company_name',
        'address',
        'designation',
        'start_date',
        'end_date',
        'salary',
        'remarks'
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

   
}

 