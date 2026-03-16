<?php

namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryGenerationPolicy extends BaseModel
{
    use HasFactory;

    protected $table    = 'salary_generation_policies';
    protected $fillable = [
    
    ];
}
