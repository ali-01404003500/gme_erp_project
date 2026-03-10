<?php
namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveYear extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'leave_years';

    protected $fillable = [
        'year',
        'start_date',
        'end_date',
        'is_closed',
        'closed_by',
        'closed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_closed'  => 'boolean',
        'closed_at'  => 'datetime',
    ];

    /**
     * Scope a query to only include the currently active leave year.
     */
    public function scopeActive($query)
    {
        return $query->where('is_closed', false);
    }

    /**
     * Relationship with the User who closed the year.
     */
    public function closedByUser()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
