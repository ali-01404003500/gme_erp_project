<?php

namespace Modules\Account\Models;

use App\Model;
use App\Traits\AutoCreateUpdateAndHistory;

class Stock extends Model
{
    use AutoCreateUpdateAndHistory;

    protected $table = 'acc_stocks';

    protected $guarded = [];

    public function stockable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->morphTo(Product::class);
    }

    public function purchaseDetails()
    {
        return $this->morphTo(PurchaseDetail::class);
    }

    public function saleDetails()
    {
        return $this->morphTo(SaleDetail::class);
    }
}
