<?php

namespace Modules\HRMS\Models\Settings;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Controllers\EmployeeApproverController;
use Modules\HRMS\Models\Employee;

class Designation extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    

    protected $guarded = [];
    public $deletePrevent = ['designations'];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'designation_id', 'id');
    }

    public function approver(){
        return $this->belongsTo(Designation::class,'designation_id');
    }
}
