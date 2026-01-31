<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\LocationManager\Models\Area;

class SalesOrderShipment extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function area() {
        return $this->belongsTo(Area::class);
    }
}
