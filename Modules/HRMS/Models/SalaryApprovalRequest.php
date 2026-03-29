<?php
// app/Models/ApprovalRequestSignatory.php

namespace Modules\HRMS\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryApprovalRequest extends Model
{
    protected $table = 'salary_approval_requests';

    protected $fillable = [
        'salary_generate_id',
        'created_by',
        'status',
        'current_level',
        'remarks',
        'approved_at',
    ];

    protected $casts = [
        'current_level' => 'integer',
        'approved_at'   => 'datetime',
    ];

    public function salaryGenerate(): BelongsTo
    {
        return $this->belongsTo(SalaryGenerate::class, 'salary_generate_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(SalaryApprovalDetail::class);
    }

    // Get current pending detail
    public function getCurrentPendingDetail()
    {
        return $this->details()
            ->where('status', 'pending')
            ->orderBy('level', 'asc')
            ->first();
    }

    // Check if all levels are approved
    public function isFullyApproved()
    {
        $totalLevels   = $this->details()->count();
        $approvedCount = $this->details()->where('status', 'approved')->count();

        return $totalLevels > 0 && $totalLevels === $approvedCount;
    }

    // Check if any level is denied
    public function isDenied()
    {
        return $this->details()->where('status', 'denied')->exists();
    }

    // Get current level
    public function getCurrentLevel()
    {
        $pendingDetail = $this->getCurrentPendingDetail();
        return $pendingDetail ? $pendingDetail->level : null;
    }
}
