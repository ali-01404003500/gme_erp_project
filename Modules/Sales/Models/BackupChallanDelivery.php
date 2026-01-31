<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BackupChallanDelivery extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    function backupChallan() :  BelongsTo 
    {
        return $this->belongsTo(BackupChallan::class, "backup_challan_id");
    }

    function backupChallanDeliveryDetails() : HasMany {
        
        return $this->hasMany(BackupChallanDeliveryDetail::class, "backup_challan_delivery_id");
    }
}
