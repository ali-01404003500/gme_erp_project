<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsInfo extends BaseModel
{
    use HasFactory;

    protected $table = 'sms_info';

    protected $primaryKey = 'id';

    protected $fillable = [
        'sms_reference',
        'sms_send_time',
        'sms_mem_id',
        'sms_to',
        'sms_text',
        'sms_status',
    ];

    protected $casts = [
        'sms_send_time' => 'datetime',
    ];

}
