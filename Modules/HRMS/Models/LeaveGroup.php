<?php
namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Settings\LeaveType;

class LeaveGroup extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;

    protected $table   = 'leave_groups';
    protected $guarded = [];

    /**
     * Many-to-Many relationship with Pivot data settings.
     */
    public function leaveTypes()
    {
        return $this->belongsToMany(
            LeaveType::class,
            'leave_group_details',
            'leave_group_id',
            'leave_type_id'
        )->withPivot([
            'allowed_balance',
            'max_leave_balance_in_year',
            'continuous_sanction',
            'max_forward_from_previous_year',
            'max_sanction_in_service_life',
            'interval_days_in_same_leave',
            'min_day_count_for_attachment',
            'max_limit_for_past_leave',
            'apply_future_leave_after_days',
            'max_balance_for_encashment',
            'is_balance_forward',
            'allow_leave_encashment',
            'balance_forwarding_on_group_change',
            'leave_allow_between_multiple_years',
            'negative_balance',
            'is_half_day',
            'continuous_days_allow',
            'is_prefix_allowed',
            'is_suffix_allowed',
            'requires_leave_attachment',
            'allow_earn_leave',
        ])->withTimestamps();
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'leave_group_id');
    }
}
