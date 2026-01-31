<?php

namespace Modules\Account\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\Sales\Models\SaleInvoice;

class CustomerPaymentDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function invoice(){
        return $this->belongsTo(SaleInvoice::class, 'invoice_id');
    }
    public function customerPaymentInvoices(){
        return $this->hasMany(CustomerPaymentInvoice::class);
    }
}
