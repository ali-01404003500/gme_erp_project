<?php

namespace Modules\Account\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\Inventory\Models\ProductCatalog;

class CustomerPaymentInvoice extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    function product(){
        return $this->belongsTo(ProductCatalog::class);
    }
}
