<?php
namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnpaidLeaveDeductionPolicy extends Model
{
    use HasFactory;

    protected $table = 'unpaid_leave_deduction_policies';

    protected $fillable = [
        'unpaid_consider',
        'unpaid_deduct_gross',
    ];

    protected $casts = [
        'unpaid_consider'     => 'boolean',
        'unpaid_deduct_gross' => 'boolean',
    ];
}
