<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionReceive extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public function receiveDetails()
    {
        return $this->hasMany(RequisitionReceiveDetail::class);
    }

    public function receiveSerials()
    {
        return $this->hasMany(RequisitionReceiveSerial::class);
    }

    public function receiveBatches()
    {
        return $this->hasMany(RequisitionReceiveBatch::class);
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function aceptedBy(){

        return $this->belongsTo(User::class, 'created_by', 'id');

    }

}
