<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Models\Setup\BankBranch;

class ServicePayment extends BaseModel
{
    use HasFactory;
    
    protected $guarded = [];

    public function serviceMyTask()
    {
        return $this->belongsTo(ServiceMyTask::class, 'service_my_task_id');
    }

    //bank, branch
    public function bank()
    {
        if(in_array($this->payment_mode, ['Cash', 'Online Deposit', 'bKash', 'Nagad', 'Rocket', 'Card Payment']))
        {
            return $this->belongsTo(BankAccount::class, 'bank_id');
        }
        return $this->belongsTo(Bank::class, 'bank_id');
    }
    public function branch()
    {
        return $this->belongsTo(BankBranch::class, 'branch_id');
    }
}
