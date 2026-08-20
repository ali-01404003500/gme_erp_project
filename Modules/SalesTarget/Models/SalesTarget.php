<?php
namespace Modules\SalesTarget\Models;

use App\Models\AccessControl\Branch;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;

class SalesTarget extends Model
{
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

 

    public function slab()
    {
        return $this->belongsTo(SalesTargetSlab::class, 'sales_target_slab_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function overrideBy()
    {
        return $this->belongsTo(User::class, 'override_by');
    }
}