<?php

namespace Modules\HRMS\Models\Kpi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class MonthlyKpiAppraisalDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function appraisal()
    {
        return $this->belongsTo(MonthlyKpiAppraisal::class);
    }

    public function responsibility()
    {
        return $this->belongsTo(ResponsibilityEntry::class, 'responsibility_entry_id');
    }
}
