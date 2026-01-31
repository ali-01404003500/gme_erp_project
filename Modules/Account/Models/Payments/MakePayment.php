<?php

namespace Modules\Account\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\KeepSign;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Transaction;

class MakePayment extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;
    
    protected $guarded = [];


    function paymentTo(){
        return $this->morphTo('payment_to');
    }

    // function paymentDetails(): MorphMany
    // {
    //     return $this->morphMany(MakePaymentDetail::class, 'paymentable');
    // }

    function paymentDetails(){
        return $this->hasMany(MakePaymentDetail::class);
    }

       /**
     * The account transactions that belong to the SalesOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
     public function transactions()
    {
        return $this->morphMany(Transaction::class , 'transactionable');
    }


    public function signature(){
        return $this->morphOne(KeepSign::class, 'keep_signatureable');
    }

    /**
     * The user that verified the MakePayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * The user that approved the MakePayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

     
}
