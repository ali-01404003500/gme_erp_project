<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionReceiveDetail extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];


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
    

}
