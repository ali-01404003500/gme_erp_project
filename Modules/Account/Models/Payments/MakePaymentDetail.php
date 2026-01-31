<?php

namespace Modules\Account\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\ChequeVerification;

class MakePaymentDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function bank()
    {
        return $this->belongsTo(BankAccount::class,'bank_id');
    }

    public function makePayment()
    {
        return $this->belongsTo(MakePayment::class);
    }

    public function paymentable()
    {
        return $this->morphTo();
    }

    public function chequeVerification(){
        return $this->morphOne(ChequeVerification::class, 'source', 'source_type', 'source_id');
    }
}
