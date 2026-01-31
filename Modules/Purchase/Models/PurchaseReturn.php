<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Payments\MakePaymentDetail;
use Modules\Account\Models\Transaction;

class PurchaseReturn extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'return_date' => 'date',
    ];
    public function purchaseReturnDetails()
    {
        return $this->hasMany(PurchaseReturnDetail::class);
    }

    public function purchaseReturnApprove(){
        return $this->hasOne(PurchaseReturnApprove::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function requisition(){
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function paymentDetails()
    {
        return $this->morphMany(MakePaymentDetail::class, 'paymentable');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * Total paid/refunded amount
     */
    public function getPaidAmountAttribute(): float
    {
        return (float) $this->paymentDetails()->sum('amount');
    }

    /**
     * Remaining amount to be refunded
     */
    public function getDueAmountAttribute(): float
    {
        return max(0, $this->net_amount - $this->paid_amount);
    }
}