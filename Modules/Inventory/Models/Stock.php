<?php

namespace Modules\Inventory\Models;

use App\Models\AccessControl\Branch;
use App\Models\BaseModel;
use Modules\Purchase\Models\RequisitionReceiveBatch;
use Modules\Purchase\Models\RequisitionReceiveSerial;
use Modules\Sales\Models\BackupChallan;
use Modules\Sales\Models\BackupChallanDeliveryStock;
use Modules\Sales\Models\SalesOrderDeliveryStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Sales\Models\SalesReturnStock;
use Modules\Purchase\Models\PurchaseReturnApproveStock	;
use Modules\Sales\Models\DeliveryStock;

class Stock extends BaseModel
{
    use HasFactory;

    protected $guarded = [];
    private $source_types = [
        RequisitionReceiveBatch::class => 'Requisition Receive Batch',
        RequisitionReceiveSerial::class => 'Requisition Receive Serial',
        SalesOrderDeliveryStock::class => 'Sale Order Delivery',
        BackupChallan::class => 'Challan',
        SalesReturnStock::class => 'Sales Return',
        DeliveryStock::class => 'Sales Order',
        PurchaseReturnApproveStock::class => 'Purchase Return',
        ProductTransferStockDetails::class => 'Product Transfer',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class);
    }

    public function source()
    {
        return $this->morphTo('source', 'source_type', 'source_id');
    }

    public function getSourceNameAttribute()
    {
        if ($this->source_type == BackupChallanDeliveryStock::class) {
            $backupChallanType = BackupChallanDeliveryStock::find($this->source_id);
            return $backupChallanType->backup_challan_type . ' ' . 'Delivery';
        } else {
            return $this->source_types[$this->source_type] ?? $this->source_type;
        }

    }
}
