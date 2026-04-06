<?php
namespace Modules\HRMS\Models;

use App\Models\BaseModel;

class LeaveEligibleEmployee extends BaseModel
{
    protected $table = 'leave_eligible_employees';

    protected $fillable = [
        'condition_type',
        'eligibility',
        'status',
    ];
}
