<?php

namespace Modules\Account\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Transaction;
use Modules\HRMS\Models\BillsAndAllowance;
use Modules\HRMS\Models\Employee;

class PettyCashPayment extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;
    
    
    protected $guarded = [];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function verifiedBy(){
        return $this->belongsTo(Employee::class, 'verified_by');
    }

    public function checkedBy(){
        return $this->belongsTo(Employee::class, 'checked_by');
    }

    public function paymentTo()
    {
        return $this->morphTo();
    }
    public function paymentDetails()
    {
        return $this->morphMany(MakePaymentDetail::class, 'paymentable');
    }

    public function bills()
    {
        return $this->hasMany(BillsAndAllowance::class, 'petty_cash_payment_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class , 'transactionable');
    }


}
