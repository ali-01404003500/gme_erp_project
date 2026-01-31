<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\LocationManager\Models\Area;

class BackupChallanShipment extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function backupChallan()
    {
        return $this->belongsTo(BackupChallan::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function area() {
        return $this->belongsTo(Area::class);
    }
}
