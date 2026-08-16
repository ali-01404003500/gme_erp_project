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

    public function payments()
    {
        return $this->hasMany(LoanPayment::class);
    }


    public function generatePaymentSchedule()
    {
        $this->payments()->delete();

        $amount = (float) $this->amount;

        $duration = (int) $this->duration;

        $monthlyAmount = (float) $this->monthly_reduction;

        $startMonth = \Carbon\Carbon::createFromFormat('Y-m',  $this->start_month  )->startOfMonth();


        for ($i = 0; $i < $duration; $i++) {
            $dueDate = $startMonth->copy()->addMonths($i);
            $remaining = $amount - ($monthlyAmount * $i);

            $paymentAmount = min( $monthlyAmount,  $remaining );

            if ($paymentAmount <= 0) {
                break;
            }

            $this->payments()->create([
                'employee_id' => $this->employee_id,
                'installment_no' => $i + 1,
                'due_date' => $dueDate->toDateString(),
                'amount' => $paymentAmount,
                'paid_amount' => 0,
                'status' => 'pending',
                'created_by' => auth()->id(),
            ]);
        }
    }
}
