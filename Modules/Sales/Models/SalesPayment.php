<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class SalesPayment extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function salesPaymentDetails(){

        return $this->hasMany(SalesPaymentDetail::class, 'sales_payment_id', 'id');
    }
}
