<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function jobApplicationExperiences()
    {
        return $this->hasMany(JobApplicationExperienes::class);
    }

    public function jobApplicationEducations()
    {
        return $this->hasMany(JobApplicationEducation::class);
    }
}
