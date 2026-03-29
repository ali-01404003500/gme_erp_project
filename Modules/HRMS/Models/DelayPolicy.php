<?php
namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DelayPolicy extends Model
{
    use HasFactory;

    protected $table = 'delay_policies';

    protected $fillable = [
        'consider_delay',
        'deduct_from_gross_salary', 
        'delay_limit',
        'adjust_days',
    ];

    protected $casts = [
        'consider_delay'             => 'boolean',
        'deduct_from_gross_salary'         => 'boolean', 
        'delay_limit'                => 'integer',
        'adjust_days'                => 'integer',
    ];
}
