<?php

namespace Modules\Sales\Models;

use App\Models\KeepSign;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Payments\MakePayment;
use Modules\Account\Models\Payments\MakePaymentDetail;
use Modules\Account\Models\Transaction;
use Modules\CRM\Models\Customer\Customer;

class SalesReturn extends BaseModel
{
    use HasFactory;
     use SoftDeletes;
    use AutoCreateUpdateAndHistory;
    
    protected $guarded = [];

    public function salesReturnDetails()
    {
        return $this->hasMany(SalesReturnDetail::class, 'sales_return_id');
    }
    public function details()
    {
        return $this->hasMany(SalesReturnDetail::class, 'sales_return_id');
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function signature()
    {
        return $this->morphOne(KeepSign::class, 'keep_signatureable');
    }


    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    
    public function paymentDetails()
    {
        return $this->morphMany(MakePaymentDetail::class, 'paymentable');
    }

    public function salesReturnStocks()
    {
        return $this->hasManyThrough(
            \Modules\Sales\Models\SalesReturnStock::class,
            \Modules\Sales\Models\SalesReturnDetail::class,
            'sales_return_id', // foreign key on SalesReturnDetail
            'sales_return_detail_id', // foreign key on SalesReturnStock
            'id', // local key on SalesReturn
            'id' // local key on SalesReturnDetail
        );
    }


}
