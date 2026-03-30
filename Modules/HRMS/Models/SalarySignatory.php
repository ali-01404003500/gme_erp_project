<?php

namespace Modules\HRMS\Models;

use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Employee;
class SalarySignatory extends Model
{
    use HasFactory, AutoCreateUpdateAndHistory;

    protected $table = 'salary_signatories';

    protected $fillable = [
        'employee_id',  
        'signatory_tag',
        'approver_level',
        'status',
        'description',
    ];

    protected $casts = [
        'approver_level'  => 'string',
        'status' => 'string',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
  
}