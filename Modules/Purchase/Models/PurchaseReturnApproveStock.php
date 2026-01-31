<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnApproveStock extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function purchaseReturnApproveDetail()
    {
        return $this->belongsTo(PurchaseReturnApproveDetail::class, 'p_r_approve_detail_id');
    }
}
