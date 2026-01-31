<?php

namespace Modules\Purchase\Models;

use App\Models\StockModel;
use Modules\Inventory\Services\StockService;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionReceiveBatch extends StockModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public function requisition(){
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function getParentIdAttribute()
    {
        return $this->requisition_id;
    }

    public function getAvaibleStockAttribute(){
        return (new StockService())->countStockByLotNo($this->lot_no);
    }


}
