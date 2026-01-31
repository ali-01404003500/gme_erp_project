<?php

namespace Modules\Account\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Transaction;
use Modules\CRM\Models\Customer\Customer;

class CustomerPayment extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;
    
    protected $guarded = [];

    function customer(){
        return $this->belongsTo(Customer::class);
    }

    function  customerPaymentDetails(){
        return $this->hasMany(CustomerPaymentDetail::class);
    }

    /**
     * The account transactions that belong to the CustomerPayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    function accountTransactions(){
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    
    public function verifyTransactions(){
        if($this->accountTransactions->sum('amount') != 0){
            throw new \Exception('debit credit not matched');
        }
    }
}
