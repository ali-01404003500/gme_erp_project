<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\LocationManager\Models\Area;

class SalesRequisitionShipment extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    public function salesRequisition()
    {
        return $this->belongsTo(SalesRequisition::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function area() {
        return $this->belongsTo(Area::class);
    }
}
