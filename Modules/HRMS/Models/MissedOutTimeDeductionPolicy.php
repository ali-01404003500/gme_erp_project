<?php
namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissedOutTimeDeductionPolicy extends Model
{
    use HasFactory;

    protected $table = 'missed_out_time_deduction_policies';

    protected $fillable = [
        'consider_missed_out',
        'deduct_from_gross',
        'consider_consecutive',
        'missed_out_limit',
        'adjust_days',
    ];

    protected $casts = [
        'consider_missed_out'  => 'boolean',
        'deduct_from_gross'    => 'boolean',
        'consider_consecutive' => 'boolean',
        'missed_out_limit'     => 'integer',
        'adjust_days'          => 'integer',
    ];
}
