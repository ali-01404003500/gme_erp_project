<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturnApprove extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];


    public function paurchaseReturn(){

        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }
    

    public function paurchaseReturnApproveDetails()
    {
        return $this->hasMany(PurchaseReturnApproveDetail::class, 'p_r_approve_id');
    }

  

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function aceptedBy(){

        return $this->belongsTo(User::class, 'created_by', 'id');

    }

}
