<?php

namespace Modules\Sales\Models;


use App\Models\StockModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BackupChallanDeliveryStock extends StockModel
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function backupChallanDeliveryDetail() : BelongsTo
    {
        return $this->belongsTo(BackupChallanDeliveryDetail::class, 'b_c_d_p_details_id');
    }
    
    function getParentIdAttribute(){
        return $this->backupChallanDeliveryDetail->backup_challan_delivery_id;
    }
}
