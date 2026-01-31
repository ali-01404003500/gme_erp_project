<?php

namespace Modules\HRMS\Models\Kpi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Settings\Designation;

class KpiSetup extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;    

    
    protected $guarded = [];

    public $deletePrevent = ['kpi_setups'];

    /**
     * Get the designation that owns the KPI setup.
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    /**
     * Get the KPI setup details associated with the KPI setup.
     */
    public function details()
    {
        return $this->hasMany(KpiSetupDetail::class, 'kpi_setup_id');
    }
}
