<?php
namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnderworkDeductionPolicy extends Model
{
    use HasFactory;

    protected $table = 'underwork_deduction_policies';

    protected $fillable = [
        'consider_underwork',
        'consider_cumulative',
        'deduct_from_salary',
        'leave_type_id',
        'hours_to_consider',
        'adjust_days',
    ];

    protected $casts = [
        'consider_underwork'  => 'boolean',
        'consider_cumulative' => 'boolean',
        'deduct_from_salary'  => 'boolean',
        'leave_type_id'       => 'integer',
        'hours_to_consider'   => 'integer',
        'adjust_days'         => 'integer',
    ];
}
