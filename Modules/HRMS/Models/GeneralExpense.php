<?php

namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Models\Account;
use Modules\HRMS\Models\Settings\ExpenseType;

class GeneralExpense extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    public function billsAndAllowance()
    {
        return $this->belongsTo(BillsAndAllowance::class);
    }

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class,'expense_type');
    }
    public function accountHead()
    {
        return $this->belongsTo(Account::class, 'account_head_id');
    }
}
