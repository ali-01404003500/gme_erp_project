<?php
namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsentPolicy extends Model
{
    use HasFactory;
    protected $table    = 'absent_policies';
    protected $fillable = [
        'consider_absent',
        'deduct_from_salary',
        'deduct_from_gross',
        'adjust_days',
    ];

    protected $casts = [
        'consider_absent'    => 'boolean',
        'deduct_from_salary' => 'boolean',
        'deduct_from_gross'  => 'boolean',
        'adjust_days'        => 'integer',
    ];
}
