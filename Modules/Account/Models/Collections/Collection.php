<?php

namespace Modules\Account\Models\Collections;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\KeepSign;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\ChequeVerification;
use Modules\Account\Models\Payment;
use Modules\Account\Models\Transaction;

class Collection extends BaseModel
{
    use HasFactory, SoftDeletes, AutoCreateUpdateAndHistory;
    
    protected $guarded = [];



    public function collectionFrom()
    {
        return $this->morphTo(null, 'collection_from_type', 'collection_from_id');
    }



    /**
     * Payments against this collection.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'collection_id', 'id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class , 'transactionable');
    }

    public function signature(){
        return $this->morphOne(KeepSign::class, 'keep_signatureable');
    }

    public function source(){
        return $this->morphTo('source', 'source_type', 'source_id');
    }
    /**
     * Get the user who verified the collection.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the user who approved the collection.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


}
