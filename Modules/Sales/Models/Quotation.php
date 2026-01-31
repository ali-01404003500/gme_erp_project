<?php

namespace Modules\Sales\Models;


use App\Models\BaseModel;
use App\Models\KeepSign;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;

class Quotation extends BaseModel
{
    use HasFactory;
    
    use SoftDeletes;
    use AutoCreateUpdateAndHistory;
    protected $guarded = [];

    public function quotationDetails()
    {
        return $this->hasMany(QuotationDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(){
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function quotationTerms()
    {
        return $this->hasOne(QuotationTermsAndCondition::class);
    }

     public function details()
    {
        return $this->hasMany(QuotationDetail::class);
    }

     public function signature(){
        return $this->morphOne(KeepSign::class, 'keep_signatureable');
    }
    public function shipment(){
        return $this->morphOne(ShipmentConditionInfo::class, 'for', 'for_type', 'for_id');
    }
}
