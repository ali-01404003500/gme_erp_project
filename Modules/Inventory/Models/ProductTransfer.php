<?php

namespace Modules\Inventory\Models;

use App\Models\AccessControl\Branch;
use App\Models\BaseModel;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\Settings\Unit;
use App\Traits\AutoCreatedUpdated;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTransfer extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public function productTransferRequest(){
        return $this->belongsTo(ProductTransferRequest::class, "product_transfer_request_id");
    }

    public function productTransferDetails(){
        return $this->hasMany(ProductTransferDetail::class);
    }

    public function destinationBranch(){
        return $this->belongsTo(Branch::class, "destination_warehouse_id");
    }

    public function sourceBranch(){
        return $this->belongsTo(Branch::class, "source_warehouse_id");
    }

    
}

