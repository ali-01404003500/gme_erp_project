<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BackupChallanDeliveryDetail extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function backupChallanDelivery() : BelongsTo
    {
        return $this->belongsTo(BackupChallanDelivery::class, 'backup_challan_delivery_id');
    }

    public function salesOrderDeliveryStocks() : HasMany
    {
        return $this->hasMany(BackupChallanDeliveryStock::class, 'b_c_d_p_details_id');
    }

    public function product() : BelongsTo
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }

    public function backupChallanDetail(){
        return $this->backupChallanDelivery->backupChallan->backupChallanDetails()->where('product_id', $this->product_id)->first();
    }
}
