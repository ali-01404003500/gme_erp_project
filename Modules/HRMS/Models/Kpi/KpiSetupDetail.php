<?php

namespace Modules\HRMS\Models\Kpi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class KpiSetupDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    /**
     * Get the KPI setup that owns the detail.
     */
    public function kpiSetup()
    {
        return $this->belongsTo(KpiSetup::class, 'kpi_setup_id');
    }
}
