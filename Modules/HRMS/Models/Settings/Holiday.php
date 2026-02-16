<?php

namespace Modules\HRMS\Models\Settings;

use App\Models\AccessControl\Branch;
use App\Models\AccessControl\BranchType;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Attendance;
use Modules\HRMS\Models\Settings\Department;


class Holiday extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public $deletePrevent = ['holidays', 'attenendances'];

    public function attenendances() {
        return $this->hasMany(Attendance::class, 'shift_id');   
    }
   
        public function department() {
            return $this->hasMany(Department::class, 'holiday_id');
        }


}
