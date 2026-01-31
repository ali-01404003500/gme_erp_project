<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'details_data' => 'json',
        'accepted_data' => 'json',
    ];

    public function sourceable()
    {
        return $this->morphTo();
    }

//@dd($salesOrder->otpVerifications->where('title', 'Credit Limit Exceeded')->first()->accepted_data['additional_data'])

    public function getAdditionalDataAttribute()
    {
        // $additionalData = json_decode($this->accepted_data, true)['additional_data'] ?? [];
        return json_decode($this->accepted_data['additional_data']??'{}', true);
    }
}
