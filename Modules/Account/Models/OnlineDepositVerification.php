<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\CRM\Models\Customer\Customer;

class OnlineDepositVerification extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;

    protected $guarded = [
        'document' => 'array',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
  
    public function account(){
        return $this->belongsTo(Account::class,'head_id');
    }
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'head_id');
    }
    public function transactions()
    {
        return $this->morphMany(Transaction::class , 'transactionable');
    }
    public function source()
    {
        return $this->morphTo('source', 'source_type', 'source_id');
    }

    public function depositedBy()
    {
        return $this->belongsTo(User::class, 'deposited_by');

    }
 
}
