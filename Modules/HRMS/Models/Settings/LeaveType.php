<?php
namespace Modules\HRMS\Models\Settings;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\LeaveApplication;

class LeaveType extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;

    public $deletePrevent = ['leaveApplications'];

    protected $guarded = [];

    // Boolean fields casting
    protected $casts = [
        'is_maternity'         => 'boolean',
        'is_unpaid'            => 'boolean',
        'is_partially_balance' => 'boolean',
    ];

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class, 'leave_type_id');
    }
}
