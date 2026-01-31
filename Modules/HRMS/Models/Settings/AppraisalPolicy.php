<?php

namespace Modules\HRMS\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppraisalPolicy extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    public $deletePrevent = ['appraisal_policies'];

    /**
     * Get the department that owns the appraisal policy.
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
}
