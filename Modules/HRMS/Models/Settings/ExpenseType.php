<?php

namespace Modules\HRMS\Models\Settings;

use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\GeneralExpense;

class ExpenseType extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    

    protected $guarded = [];

    public $deletePrevent = ['generalExpenses'];

    public function generalExpenses()
    {
        return $this->hasMany(GeneralExpense::class, 'expense_type');
    }


}
