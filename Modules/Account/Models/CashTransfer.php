<?php

namespace Modules\Account\Models;

use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashTransfer extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;

    protected $guarded = [];

    public function fromEmployee()
    {
        return $this->belongsTo(\Modules\HRMS\Models\Employee::class, 'from_employee_id');
    }

    public function toEmployee()
    {
        return $this->belongsTo(\Modules\HRMS\Models\Employee::class, 'to_employee_id');
    }
}
