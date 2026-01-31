<?php

namespace Modules\Account\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\Purchase\Models\PurchaseOrderReceive;

class SupplierPaymentDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function supplierPayment(){
        return $this->belongsTo(SupplierPayment::class);
    }

    public function receive(){
        return $this->belongsTo(PurchaseOrderReceive::class);
    }
}
