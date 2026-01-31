<?php

namespace App\Models\AccessControl;

use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommercialInfo extends Model
{
    use HasFactory;
    protected $guarded = [];
}
