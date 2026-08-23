<?php

namespace App\Observers;

use Modules\Sales\Models\SalesOrder;
use Modules\SalesTarget\Models\SalesOrderEmployeeSplit;
use Modules\SalesTarget\Services\SalesTargetService;

class SalesOrderEmployeeSplitObserver
{
    public function __construct(protected SalesTargetService $salesTargetService) {}

    public function saved(SalesOrderEmployeeSplit $split)
    {
        $order = SalesOrder::find($split->sales_order_id);
        if ($order) {
            $this->salesTargetService->syncOrderAchievement($order);
        }
    }

    public function deleted(SalesOrderEmployeeSplit $split)
    {
        $order = SalesOrder::find($split->sales_order_id);
        if ($order) {
            $this->salesTargetService->syncOrderAchievement($order);
        }
    }
}
