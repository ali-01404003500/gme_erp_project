<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class JobApplicationEducation extends BaseModel
{
    use HasFactory;
    
    protected $table = 'job_application_educations';
    protected $guarded = [];
}
