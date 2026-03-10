<?php
namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnpaidLeaveDeductionPolicy extends Model
{
    use HasFactory;

    // Database table name specification
    protected $table = 'unpaid_leave_deduction_policies';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'unpaid_consider',
        'unpaid_deduct_gross',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'unpaid_consider'     => 'boolean',
        'unpaid_deduct_gross' => 'boolean',
    ];
}
