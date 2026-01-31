<?php

namespace Modules\Account\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Transaction;
use Modules\CRM\Models\Customer\BrokerBank;
use Modules\Sales\Models\SalesCommission;

class BrokerPayment extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;
    
    protected $guarded = [];

    public function salesCommission()
    {
        return $this->belongsTo(SalesCommission::class, 'sales_commission_id');
    }

    public function brokerPaymentBank()
    {
        return $this->belongsTo(BrokerBank::class, 'broker_payment_bank_id');
    }

     public function transactions()
    {
        return $this->morphMany(Transaction::class , 'transactionable');
    }
}
