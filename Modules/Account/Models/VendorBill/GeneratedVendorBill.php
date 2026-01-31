<?php

namespace Modules\Account\Models\VendorBill;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Transaction;

class GeneratedVendorBill extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes ;
    
    protected $guarded = [];


    public function setting()
    {
        return $this->belongsTo(VendorBillSetting::class, 'setting_id');
    }

    public function billFor()
    {
        return $this->morphTo('bill_for');
    }

    /**
     * The account transactions that belong to the SalesOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
     public function transactions()
    {
        return $this->morphMany(Transaction::class , 'transactionable');
    }
}
