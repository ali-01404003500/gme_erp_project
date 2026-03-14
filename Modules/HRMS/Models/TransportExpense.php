<?php

namespace Modules\HRMS\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Account\Models\Account;
use Modules\HRMS\Models\Settings\TransportType;

class TransportExpense extends BaseModel
{
    use HasFactory;

    protected $guarded = [];


    public function billsAndAllowance()
    {
        return $this->belongsTo(BillsAndAllowance::class);
    }
    public function transportType()
    {
        return $this->belongsTo(TransportType::class,'transport_by');
    }
    public function accountHead()
    {
        return $this->belongsTo(Account::class, 'account_head_id');
    }
}
