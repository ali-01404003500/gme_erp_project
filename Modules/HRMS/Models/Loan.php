<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
