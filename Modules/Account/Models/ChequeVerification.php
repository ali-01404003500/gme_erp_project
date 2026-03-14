<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Models\Setup\BankBranch;
use Modules\CRM\Models\Customer\Customer;

class ChequeVerification extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;
    
    protected $guarded = [];
    protected $casts = [
        'document' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function branch()
    {
        return $this->belongsTo(BankBranch::class, 'branch_id');
    }

    public function account(){
        return $this->belongsTo(Account::class,'head_id');
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

    public function chequeDishonorSummaries()
    {
        return $this->hasMany(ChequeDishonorSummary::class,'cheque_verification_id');
    }
     
}
