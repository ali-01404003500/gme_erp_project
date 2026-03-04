<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Account;
use Modules\Account\Models\Payments\MakePaymentDetail;
use Modules\Account\Models\Transaction;

class Loan extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];
   use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    /**
     * Get the employee that owns the loan.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function details()
    {
        return $this->hasMany(LoanDetail::class);
    }

    // Relationship: Transactions
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function paymentDetails()
    {
        return $this->morphMany(MakePaymentDetail::class, 'paymentable');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
