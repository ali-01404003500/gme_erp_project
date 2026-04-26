<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Settings\Designation;

class Approver extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];


public function employee(){
    return $this->belongsTo(Employee::class,'employee_id');
}

public function approver(){
    return $this->belongsTo(Employee::class,'approver_id');
}


   
}
