<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeFamilyContact extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory; 
    
    protected $fillable = [
        'employee_id',
        'name',
        'relationship',
        'gender',
        'nid',
        'profession',
        'contact_no'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

   
}

 