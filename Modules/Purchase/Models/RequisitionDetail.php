<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequisitionDetail extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function product(){
        return $this->belongsTo(ProductCatalog::class, 'product_id', 'id');
    }

    public function requisition(){
        return $this->belongsTo(Requisition::class, 'requisition_id', 'id');
    }

    public function stocks(){
        if($this->product->is_serial_product){
            return RequisitionReceiveSerial::where('requisition_id', $this->requisition_id)
                ->where('product_id', $this->product_id);
            
        }else{
            return RequisitionReceiveBatch::where('requisition_id', $this->requisition_id)
                ->where('product_id', $this->product_id);
        }
    }
}
