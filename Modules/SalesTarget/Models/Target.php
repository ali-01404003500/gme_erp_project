<?php

namespace Modules\SalesTarget\Models;

use Modules\HRMS\Models\Employee;

use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected $table = 'sales_target';
    protected $primaryKey = 'target_id';

    protected $fillable = [
        'employee_id',
        'jan_target',
        'feb_target',
        'mar_target',
        'apr_target',
        'may_target',
        'jun_target',
        'jul_target',
        'aug_target',
        'sep_target',
        'oct_target',
        'nov_target',
        'dec_target',
        'total_target',
        'year'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
