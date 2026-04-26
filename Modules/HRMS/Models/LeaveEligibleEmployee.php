<?php

namespace Modules\HRMS\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveEligibleEmployee extends BaseModel
{
    protected $table = 'leave_eligible_employees'; 

    protected $fillable = [
        'condition_type',
        'eligibility',
        'status',
    ];
}
 