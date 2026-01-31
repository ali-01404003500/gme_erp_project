<?php

namespace Modules\Services\Models;

use App\Models\BaseModel;
use App\Models\OtpVerification;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Payment;
use Modules\Sales\Models\ShipmentConditionInfo;

class ServiceMyTask extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;

    protected $guarded = [];

    protected $casts=[
        'attachments' => 'array',
    ];

    public function serviceToken()
    {
        return $this->belongsTo(ServiceToken::class, 'service_token_id');
    }

    public function pendingServiceTokens()
    {
        return $this->hasMany(ServicePendingToken::class, 'service_my_task_id');
    }

    public function bills()
    {
        return $this->hasMany(MyServiceBill::class, 'service_my_task_id');
    }

    public function returnBills()
    {
        return $this->hasMany(ServiceReturnBill::class, 'service_my_task_id');
    }


    // public function payments()
    // {
    //     return $this->hasMany(ServicePayment::class, 'service_my_task_id');
    // }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function otpVerifications(){
        return $this->morphMany(OtpVerification::class, 'sourceable');
    }

    public function shipment(){
        return $this->morphOne(ShipmentConditionInfo::class, 'for', 'for_type', 'for_id');
    }
}
