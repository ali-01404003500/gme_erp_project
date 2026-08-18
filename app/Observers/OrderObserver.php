<?php

namespace App\Observers;

use App\Models\Order;
use Modules\Sales\Models\SalesOrder;
use Modules\SalesTarget\Services\SalesTargetService;

class OrderObserver
{

    public function __construct(protected SalesTargetService $salesTargetService) {}

    public function updated(SalesOrder $order)
    {
        // status "completed"/"paid" হওয়ার মুহূর্তে target-এ achievement যোগ হবে
        if ($order->isDirty('status') && $order->status === 'completed') {
            $this->salesTargetService->recordAchievement(
                $order->employee_id,
                $order->total_amount,
                $order->updated_at
            );
        }
    }

    // যদি order তৈরি হওয়ার সাথে সাথেই "completed" ধরে নেন
    public function created(SalesOrder $order)
    {
        if ($order->status === 'completed') {
            $this->salesTargetService->recordAchievement(
                $order->employee_id,
                $order->total_amount,
                $order->created_at
            );
        }
    }
 
    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(SalesOrder $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(SalesOrder $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(SalesOrder $order): void
    {
        //
    }
}
