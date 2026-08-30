<?php

namespace Modules\Purchase\Models;

use App\Models\BaseModel;
use Modules\Inventory\Models\Product\Settings\Brand;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;

class PurchaseOrder extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];
  
    protected $fillable = [
        'po_date', 'po_number', 'supplier_id', 'search_by_brand_id', 'transport_title',
        'remarks', 'shipping_method', 'shipping_terms', 'delivery_date',
        'total_amount', 'transport_cost', 'net_amount', 'currency', 'incoterm',
        'booking_exchange_rate', 'status', 'created_by', 'approved_by',
        'sent_to_supplier_at', 'supplier_acknowledged_at',
    ];

    protected $casts = [
        'po_date' => 'date',
        'delivery_date' => 'date',
        'sent_to_supplier_at' => 'datetime',
        'supplier_acknowledged_at' => 'datetime',
    ];


    // Status constants — আপনার business অনুযায়ী দরকার হলে মান বদলান
    public const STATUS_DRAFT = 0;
    public const STATUS_PENDING_APPROVAL = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_SENT_TO_SUPPLIER = 3;
    public const STATUS_PI_RECEIVED = 4;
    public const STATUS_CLOSED = 5;
    public const STATUS_CANCELLED = 9;

    public const STATUS_LABELS = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_PENDING_APPROVAL => 'Pending Approval',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_SENT_TO_SUPPLIER => 'Sent to Supplier',
        self::STATUS_PI_RECEIVED => 'PI Received',
        self::STATUS_CLOSED => 'Closed',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? 'Unknown';
    }


    public function brand(){
        return $this->belongsTo(Brand::class);
    }

     public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    
    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
}
