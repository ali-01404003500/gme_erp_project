<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturnApproveDetail extends BaseModel
{
    use HasFactory;
    protected $guarded = [];


    public function purchaseReturnApprove()
    {
        return $this->belongsTo(PurchaseReturnApprove::class);
    }


    public function receive()
    {
        return $this->belongsTo(RequisitionReceive::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class);
    }

    public function requitions(){

        return $this->belongsTo(Requisition::class,'requisition_id', 'id');
    }

    public function purchaseReturnApproveStocks(){

        return $this->hasMany(PurchaseReturnApproveStock::class, 'p_r_approve_detail_id');
    }
}
