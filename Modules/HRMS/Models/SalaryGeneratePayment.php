<?php

namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryGeneratePayment extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    public function salaryGenerate()
    {
        return $this->belongsTo(SalaryGenerate::class);
    }
}
