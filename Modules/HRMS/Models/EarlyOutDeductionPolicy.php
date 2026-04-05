<?php
namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarlyOutDeductionPolicy extends Model
{
    use HasFactory;
    protected $table    = 'early_out_deduction_policies';
    protected $fillable = [
        'consider_early_out',
        'deduct_from_gross', 
        'early_out_limit',
        'adjust_days',
    ];

    protected $casts = [
        'consider_early_out'             => 'boolean',
        'deduct_from_gross'              => 'boolean', 
        'early_out_limit'                => 'integer',
        'adjust_days'                    => 'integer',
    ];
}
