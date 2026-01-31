<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeepSign extends Model
{
    use HasFactory;

    protected $fillable = [
        'signature',
        'keep_signatureable_type',
        'keep_signatureable_id',
    ];
}
