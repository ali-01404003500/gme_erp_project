<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Models\Setup\BankBranch;
use Modules\Account\Models\EMIEntry;
use Modules\Account\Models\ChequeVerification;

class Payment extends BaseModel
{
    use HasFactory, SoftDeletes, AutoCreateUpdateAndHistory;
    
    protected $guarded = [];

    public function paymentable()
    {
        return $this->morphTo();
    }

     //bank, branch
    public function bank()
    {
        // dd($this->pay_mode);

        if(in_array($this->pay_mode, ['Cash', 'Online Deposit', 'bKash', 'Nagad', 'Rocket', 'Card Payment']))
        {
            return $this->belongsTo(BankAccount::class, 'bank_id', 'id');
        }
        if( $this->pay_mode == 'EMI')
        {
            // dd($this->pay_mode);
            return $this->belongsTo(EMIEntry::class, 'e_m_i_entries_id', 'id')->withTrashed();
        }
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }

    public function getBank()
    {
        if(in_array($this->pay_mode, ['Cash', 'Online Deposit', 'bKash', 'Nagad', 'Rocket', 'Card Payment']))
        {
            return BankAccount::find($this->bank_id);
        }
        if( $this->pay_mode == 'EMI')
        {
            // dd($this->pay_mode);
            return EMIEntry::find($this->e_m_i_entries_id);
        }
        return Bank::find($this->bank_id);
    }

/**
 * Get the branch record associated with the payment.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
    public function branch()
    {
        return $this->belongsTo(BankBranch::class, 'branch_id', 'id');
    }


    public function chequeVerification(){
        return $this->morphOne(ChequeVerification::class, 'source', 'source_type', 'source_id');
    }

    public function onlineDepositVerification(){
        return $this->morphOne(OnlineDepositVerification::class, 'source', 'source_type', 'source_id');
    }

    public function mfsVerification(){
        return $this->morphOne(MFSVerification::class, 'source', 'source_type', 'source_id');
    }


}
