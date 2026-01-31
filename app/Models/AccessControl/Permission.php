<?php

namespace App\Models\AccessControl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissionMaster(){
        return $this->belongsTo(PermissionMaster::class);
    }
}
