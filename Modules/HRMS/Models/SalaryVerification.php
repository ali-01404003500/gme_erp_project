<?php

namespace Modules\HRMS\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Modules\HRMS\Models\Employee;
class SalaryVerification extends Model
{
    use HasFactory;

    protected $table = 'salary_verifications';

    protected $fillable = ['salary_id', 'payroll_id', 'approver_id', 'reference_type', 'approver_level', 'status', 'approved_at'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
 
  
}