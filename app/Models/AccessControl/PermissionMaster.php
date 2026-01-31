<?php

namespace App\Models\AccessControl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermissionMaster extends Model
{
    use HasFactory;

    public function permissions():HasMany{
        return $this->hasMany(Permission::class);
    }

    public function parentMaster():BelongsTo{
        return $this->belongsTo(PermissionMaster::class, "parent_id");
    }
    public function subMasters():HasMany{
        return $this->hasMany(PermissionMaster::class,"parent_id");
    }
}
