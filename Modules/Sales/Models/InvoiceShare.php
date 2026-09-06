<?php

namespace Modules\Sales\Models;


use App\Models\BaseModel;
use App\Models\KeepSign;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;
use Illuminate\Support\Str;

class InvoiceShare extends Model
{
    protected $fillable = [
        'token', 'sales_order_id', 'pdf_path', 'customer_phone',
        'max_views', 'view_count', 'expires_at',
        'last_viewed_at', 'last_viewed_ip', 'is_revoked',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'is_revoked' => 'boolean',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    public function isExpired(): bool
    {
        return $this->is_revoked
            || now()->greaterThan($this->expires_at)
            || $this->view_count >= $this->max_views;
    }

    public static function generateToken(): string
    {
        return (string) Str::uuid();
    }
}