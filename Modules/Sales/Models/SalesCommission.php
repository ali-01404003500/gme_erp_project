<?php

namespace Modules\Sales\Models;

use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Payments\BrokerPayment;
use Modules\Account\Models\Transaction;
use Modules\CRM\Models\Customer\Broker;

class SalesCommission extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;

    protected $guarded = [];

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function brokerPayments()
    {
        return $this->hasMany(BrokerPayment::class, 'sales_commission_id');
    }

    public function getApprovedPaidAmountAttribute()
    {
        return $this->brokerPayments()->where('status', 'Approved')->sum('payment_amount');
    }

    /**
     * The account transactions that belong to the SalesOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
