<?php

namespace Modules\Account\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\User;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\ChequeVerification;

class MakePaymentDetail extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];
    protected $casts = ['attachments'=>'array', 'date' => 'datetime'];

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

    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function verifiedBy(){
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function chequeVerification(){
        return $this->morphOne(ChequeVerification::class, 'source', 'source_type', 'source_id');
    }
}
