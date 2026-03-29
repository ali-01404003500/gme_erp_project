<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryApprovalDetail extends Model
{
    protected $table = 'salary_approval_details';

    protected $fillable = [
        'salary_approval_request_id',
        'salary_signatory_id',
        'level',
        'status',
        'remarks',
        'actioned_at',
    ];

    protected $casts = [
        'level'       => 'integer',
        'actioned_at' => 'datetime',
    ];

    public function approvalRequest(): BelongsTo
    {
        return $this->belongsTo(SalaryApprovalRequest::class);
    }

    public function signatory(): BelongsTo
    {
        return $this->belongsTo(SalarySignatory::class, 'salary_signatory_id');
    }

    public function approve($remarks = null)
    {
        $this->update([
            'status'      => 'approved',
            'remarks'     => $remarks,
            'actioned_at' => now(),
        ]);
    }

    public function deny($remarks = null)
    {
        $this->update([
            'status'      => 'denied',
            'remarks'     => $remarks,
            'actioned_at' => now(),
        ]);
    }
}
