<?php
namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Employee;

class LeaveStatus extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     */
    protected $table = 'leave_statuses';

    /**
     * The attributes that aren't mass assignable.
     *
     */
    protected $guarded = [];

    /**
     * Relationship: The employee this status belongs to.
     *
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Relationship: The leave group assigned to the employee.
     *
     */
    public function leaveGroup()
    {
        return $this->belongsTo(LeaveGroup::class, 'leave_group_id');
    }

    /**
     * Relationship: The leave year this status is active for.
     *
     */
    public function leaveYear()
    {
        return $this->belongsTo(LeaveYear::class, 'leave_year_id');
    }
}
