<?php

namespace Modules\HRMS\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model; 
use Modules\HRMS\Models\ApprovalFlow;

class ApprovalRequest extends Model
{
    protected $table = 'approval_requests';

    protected $fillable = [
        'workflow_id',
        'reference_id',
        'reference_type',
        'level',
        'approver_id',
        'status',
        'approved_at',
        'remarks'
    ];

    protected $casts = [
        'approved_at' => 'datetime'
    ];

    /**
     * Workflow relation
     */
    public function workflow()
    {
        return $this->belongsTo(ApprovalFlow::class, 'workflow_id');
    }

    /**
     * Approver relation
     */
    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approver_id');
    }

    /**
     * Polymorphic reference
     */
    public function reference()
    {
        return $this->morphTo(null, 'reference_type', 'reference_id');
    }
}
  