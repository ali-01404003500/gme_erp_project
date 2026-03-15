<?php
namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryGenerationPolicy extends BaseModel
{
    use HasFactory;

    protected $table    = 'salary_generation_policies';
    protected $fillable = [
        'calculation_type',
        'fixed_days',
        'is_rounded_salary',
        'is_salary_end_date_different',
    ];
}
