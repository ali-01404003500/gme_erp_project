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
    protected $fillable = [ 
        'leave_type_name',
        'flag',
        'half_flag',
        'total_day',
        'simultaneously_limit',
        'is_maternity',
        'is_unpaid',
        'payment_mode',
        'is_partially_balance',
        'leave_count_type',
        'leave_count_policy',
    ];

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
