<?php

namespace App\Models\Notifications;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneralNotification extends BaseModel
{
    use HasFactory;

    protected $guarded = [];


    public function users(){
        return $this->belongsToMany(User::class, 'user_general_notification', 'general_notification_id', 'user_id');
    }
}
