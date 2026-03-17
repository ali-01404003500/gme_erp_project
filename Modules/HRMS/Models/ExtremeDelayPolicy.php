<?php
namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtremeDelayPolicy extends Model
{
    use HasFactory;

    protected $table = 'extreme_delay_policies';

    protected $fillable = [
        'consider_extreme_delay',
        'deduct_from_salary',
        'consider_consecutive_extreme_delay',
        'extreme_delay_limit',
        'adjust_days',
    ];
    protected $casts = [
        'consider_extreme_delay'             => 'boolean',
        'deduct_from_salary'                 => 'boolean',
        'consider_consecutive_extreme_delay' => 'boolean',
        'extreme_delay_limit'                => 'integer',
        'adjust_days'                        => 'integer',
    ];
}
