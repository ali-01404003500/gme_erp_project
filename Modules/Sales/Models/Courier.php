<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    protected $guarded = [];

    public $deletePrevent = ['salesOrderShipments'];

    public function salesOrderShipments()
    {
        return $this->hasMany(SalesOrderShipment::class, 'courier_id');
    }
}
