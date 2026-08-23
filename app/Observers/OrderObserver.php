<?php

namespace App\Observers;

use Modules\Sales\Models\SalesOrder;
use Modules\SalesTarget\Services\SalesTargetService;

class OrderObserver
{
    public function __construct(protected SalesTargetService $salesTargetService) {}

    public function created(SalesOrder $order)
    {
        $this->salesTargetService->syncOrderAchievement($order);
    }

    public function updated(SalesOrder $order)
    {
        // status, amount, employee - যেকোনো কিছু বদলালেই এটা সব ঠিক করে দেবে
        $this->salesTargetService->syncOrderAchievement($order);
    }

    public function deleted(SalesOrder $order): void
    {
        $this->salesTargetService->syncOrderAchievement($order, forceEmpty: true);
    }

    public function restored(SalesOrder $order): void
    {
        $this->salesTargetService->syncOrderAchievement($order);
    }

    public function forceDeleted(SalesOrder $order): void
    {
        //
    }
}