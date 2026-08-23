<?php
namespace Modules\SalesTarget\Models;

use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Employee;

class SalesOrderEmployeeSplit extends Model
{
    protected $fillable = ['sales_order_id', 'employee_id', 'percentage'];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id');
    }
}
