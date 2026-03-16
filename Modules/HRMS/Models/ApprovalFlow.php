<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model; 
use Modules\HRMS\Models\ApprovalRequest; // Add this for requests relation 
use Modules\HRMS\Models\ApproverStep;

class ApprovalFlow extends Model
{ 

    protected $fillable = [
        'name'
    ];

    /**
     * Approver Steps
     */
    public function steps()
    {
        return $this->hasMany(ApproverStep::class, 'workflow_id');
    }

    /**
     * Approval Requests
     */
    public function requests()
    {
        return $this->hasMany(ApprovalRequest::class, 'workflow_id');
    }
}