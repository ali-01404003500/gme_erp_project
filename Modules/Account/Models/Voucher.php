<?php


namespace Modules\Account\Models;

use App\Models\Company;
use App\Models\User;
use App\Traits\AutoCreatedUpdated;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use AutoCreateUpdateAndHistory, SoftDeletes;


    // public function company()
    // {
    //     return $this->belongsTo(Company::class);
    // }



    public function details()
    {
        return $this->hasMany(VoucherDetail::class, 'voucher_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }


    public function scopePayment($query)
    {
        $query->where('voucher_type', 'Payment');
    }




    public function scopeReceive($query)
    {
        $query->where('voucher_type', 'Receive');
    }




    public function scopeContra($query)
    {
        $query->where('voucher_type', 'Contra');
    }




    public function scopeJournal($query)
    {
        $query->where('voucher_type', 'Journal');
    }
}
