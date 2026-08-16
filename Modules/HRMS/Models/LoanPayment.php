<?php
namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Transaction;

class LoanPayment extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [

        'loan_id',
        'employee_id',

        'installment_no',
        'due_date',

        'amount',
        'paid_amount',

        'payment_date',

        'payment_method',
        'reference_no',
        'remarks',

        'status',

        'checked_by',
        'checked_at',

        'approved_by',
        'approved_at',

        'transaction_id',

        'created_by',
        'updated_by',

    ];


    protected $casts = [

        'due_date'     => 'date',
        'payment_date' => 'date',

        'checked_at'   => 'datetime',
        'approved_at'  => 'datetime',

        'amount'       => 'decimal:2',
        'paid_amount'  => 'decimal:2',

    ];


    /*
    |--------------------------------------------------------------------------
    | Loan
    |--------------------------------------------------------------------------
    */

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
    */

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Checker
    |--------------------------------------------------------------------------
    */

    public function checker()
    {
        return $this->belongsTo(
            User::class,
            'checked_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approver
    |--------------------------------------------------------------------------
    */

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Accounting Transaction
    |--------------------------------------------------------------------------
    */

    public function transaction()
    {
        return $this->belongsTo(
            Transaction::class,
            'transaction_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Remaining Amount
    |--------------------------------------------------------------------------
    */

    public function getRemainingAmountAttribute()
    {
        return max(
            0,
            (float) $this->amount - (float) $this->paid_amount
        );
    }
}
