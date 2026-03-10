<?php
namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsentPolicy extends Model
{
    use HasFactory;

    // Database table name specification (jodi migration-e 'absent_policies' diye thaken)
    protected $table = 'absent_policies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'consider_absent',
        'deduct_from_salary',
        'deduct_from_gross',
        'adjust_days',
    ];

    /**
     * Boolean field-gulo ke automatically casting korar jonno (optional but recommended)
     */
    protected $casts = [
        'consider_absent'    => 'boolean',
        'deduct_from_salary' => 'boolean',
        'deduct_from_gross'  => 'boolean',
        'adjust_days'        => 'integer',
    ];
}
