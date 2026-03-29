<?php

namespace Modules\HRMS\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\HRMS\Models\Employee;
class SalarySignatory extends Model
{
    protected $table = 'salary_signatories';

    protected $fillable = [
        'employee_id',  
        'signatory_tag',
        'level',
        'status',
        'description',
    ];

    protected $casts = [
        'level'  => 'integer',
        'status' => 'string',
    ];


    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }   

    public function approvalDetails(): HasMany
    {
        return $this->hasMany(SalaryApprovalDetail::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrderedByLevel($query)
    {
        return $query->orderBy('level', 'asc');
    }

    // Get all active signatories
    public static function getActiveSignatories()
    {
        return self::active()->orderedByLevel()->get();
    }

    // Get next level signatory
    public static function getNextLevelSignatory($currentLevel)
    {
        return self::active()
            ->where('level', '>', $currentLevel)
            ->orderBy('level', 'asc')
            ->first();
    }
}