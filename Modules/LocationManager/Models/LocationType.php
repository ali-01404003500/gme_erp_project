<?php

namespace Modules\LocationManager\Models;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationType extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    public $deletePrevent = ['locations'];

    protected $guarded = [];


    public function locations()
    {
        return $this->hasMany(Location::class,'location_type_id');
    }
}
