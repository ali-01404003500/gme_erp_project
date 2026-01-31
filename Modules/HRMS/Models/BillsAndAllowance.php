<?php

namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillsAndAllowance extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'checked_by_team_leader_date' => 'datetime',
        'checked_by_accounts_date' => 'datetime',
        'final_approved_date' => 'datetime',
        'payment_date' => 'datetime',
    ];

    protected $appends = ['total_requested_amount', 'accounts_approved_total','final_approved_total' ];

  

    public function transportExpenses()
    {
        return $this->hasMany(TransportExpense::class, 'bills_and_allowance_id');
    }

    public function generalExpenses()
    {
        return $this->hasMany(GeneralExpense::class, 'bills_and_allowance_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function checkedByTeamLeader()
    {
        return $this->belongsTo(User::class, 'checked_by_team_leader');
    }

    public function checkedByAccounts()
    {
        return $this->belongsTo(User::class, 'checked_by_accounts');
    }

    public function finalApprovedBy()
    {
        return $this->belongsTo(User::class, 'final_approved_by');
    }

    public function paymentBy()
    {
        return $this->belongsTo(User::class, 'payment_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function recommendedBy()
    {
        return $this->belongsTo(User::class, 'recommended_by');
    }


    // Calculate total requested amount
    public function getTotalRequestedAmountAttribute()
    {
        $transportTotal = $this->transportExpenses->sum('amount');
        $generalTotal = $this->generalExpenses->sum('amount');
        return $transportTotal + $generalTotal;
    }


    // Calculate team leader approved total
    public function getTeamLeaderApprovedTotalAttribute()
    {
        $transportTotal = $this->transportExpenses->sum('team_leader_approved_amount');
        $generalTotal = $this->generalExpenses->sum('team_leader_approved_amount');
        return $transportTotal + $generalTotal;
    }

    // Calculate accounts approved total
    public function getAccountsApprovedTotalAttribute()
    {
        $transportTotal = $this->transportExpenses->sum('accounts_approved_amount')??0;
        $generalTotal = $this->generalExpenses->sum('accounts_approved_amount')??0;
        return $transportTotal + $generalTotal;
    }

    // Calculate final approved total
    public function getFinalApprovedTotalAttribute()
    {
        $transportTotal = $this->transportExpenses->sum('final_approved_amount')??0;
        $generalTotal = $this->generalExpenses->sum('final_approved_amount')??0;
        return $transportTotal + $generalTotal;
    }

    
}