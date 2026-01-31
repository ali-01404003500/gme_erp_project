<?php

namespace Modules\Account\Models\IOURequisition;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Transaction;
use Modules\HRMS\Models\Employee;

class IOURequisitionEntry extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;
    
    protected $casts = [
        'date' => 'date',
    ];

    protected $guarded = [];

    // Relationship: Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // Relationship: Transactions
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

}
