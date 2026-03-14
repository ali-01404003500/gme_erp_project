<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\Transaction;
use Modules\HRMS\Models\Employee;


class FundTransfer extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;
    
    protected $guarded = [];
 
    protected $casts = [
        'date' => 'date',
        'attachments' => 'array',
    ];
  
    // Relationship: from bank account
    public function transferFromBankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'transfer_from');
    }

    // Relationship: to bank account
    public function transferToBankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'transfer_to');
    }

    // Relationship: Transactions
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    // Relationship: approve by
    public function approveBy()
    {
        return $this->belongsTo(User::class,'approve_by');

    }

    // Relationship: approve by
    public function verifyBy()
    {
        return $this->belongsTo(User::class,'verify_by');

    }


}

