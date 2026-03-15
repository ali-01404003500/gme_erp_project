<?php

namespace Modules\Inventory\Models;

use App\Models\AccessControl\Branch;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTransferReceive extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public function productTransfer()
    {
        return $this->belongsTo(ProductTransfer::class, 'product_transfer_id');
    }

    public function productTransferReceiveDetails()
    {
        return $this->hasMany(ProductTransferReceiveDetail::class);
    }

    public function destinationBranch()
    {
        return $this->belongsTo(Branch::class, 'destination_warehouse_id');
    }

    public function sourceBranch()
    {
        return $this->belongsTo(Branch::class, 'source_warehouse_id');
    }
}
