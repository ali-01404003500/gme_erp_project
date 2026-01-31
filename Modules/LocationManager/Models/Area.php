<?php

namespace Modules\LocationManager\Models;

use App\Models\BaseModel;
use App\Models\GeoLocation;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends BaseModel
{
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    use HasFactory;
    protected $guarded = [];

    public function division()
    {
        return $this->belongsTo(GeoLocation::class, 'division_id');
    }

    public function district()
    {
        return $this->belongsTo(GeoLocation::class, 'district_id');
    }

    public function thana()
    {
        return $this->belongsTo(GeoLocation::class, 'thana_id');
    }

    public function union()
    {
        return $this->belongsTo(GeoLocation::class, 'union_id');
    }

    public function village()
    {
        return $this->belongsTo(GeoLocation::class, 'village_id');
    }

}
