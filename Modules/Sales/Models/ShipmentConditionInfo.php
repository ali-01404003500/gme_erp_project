<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class ShipmentConditionInfo extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function courier(){
        return $this->belongsTo(Courier::class);

    }


    public function for()
    {
        return $this->morphTo(__FUNCTION__, 'for_type', 'for_id');
    }
}
