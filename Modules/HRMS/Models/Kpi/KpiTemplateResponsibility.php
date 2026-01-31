<?php

namespace Modules\HRMS\Models\Kpi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class KpiTemplateResponsibility extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];
    public function kpiTemplate()
    {
        return $this->belongsTo(KpiTemplate::class);
    }

    public function responsibilityEntry()
    {
        return $this->belongsTo(ResponsibilityEntry::class, 'responsibility_entriy_id');
    }
}
