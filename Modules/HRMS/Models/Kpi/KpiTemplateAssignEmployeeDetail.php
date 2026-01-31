<?php

namespace Modules\HRMS\Models\Kpi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class KpiTemplateAssignEmployeeDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function responsibility()
    {
        return $this->belongsTo(ResponsibilityEntry::class, 'responsibility_entry_id');
    }

    public function kpiAssignment()
    {
        return $this->belongsTo(KpiTemplateAssignEmployee::class);
    }
}
