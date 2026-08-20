<?php

namespace App\Observers;

use Modules\Sales\Models\SalesOrder;
use Modules\SalesTarget\Services\SalesTargetService;

class OrderObserver
{
    public function __construct(protected SalesTargetService $salesTargetService) {}

    public function updated(SalesOrder $order)
    {
        $originalStatus = $order->getOriginal('status');
        $newStatus = $order->status;

        $statusChanged = $order->isDirty('status');
        $amountChanged = $order->isDirty('total_amount');

        // Case 1: Pending/অন্য কিছু → Approved (নতুন করে approve হলো)
        if ($statusChanged && $newStatus === 'approved' && $originalStatus !== 'approved') {
            $this->salesTargetService->recordAchievement(
                $order->user_ref_id,
                $order->total_amount,
                $order->updated_at
            );
            return;
        }

        // Case 2: Approved → Pending/অন্য কিছু (approve থেকে ফিরে গেলো, বিয়োগ করতে হবে)
        if ($statusChanged && $newStatus !== 'approved' && $originalStatus === 'approved') {
            $this->salesTargetService->recordAchievement(
                $order->user_ref_id,
                -1 * $order->getOriginal('total_amount'), // যে amount আগে যোগ হয়েছিল সেটাই বিয়োগ হবে
                $order->updated_at
            );
            return;
        }

        // Case 3: Approved অবস্থাতেই আছে, কিন্তু amount edit হয়েছে (status change হয়নি)
        if (!$statusChanged && $newStatus === 'approved' && $amountChanged) {
            $difference = $order->total_amount - $order->getOriginal('total_amount');

            $this->salesTargetService->recordAchievement(
                $order->user_ref_id,
                $difference, // positive হলে যোগ হবে, negative হলে বিয়োগ হবে
                $order->updated_at
            );
        }
    }

    public function created(SalesOrder $order)
    {
        if ($order->status === 'approved') {
            $this->salesTargetService->recordAchievement(
                $order->user_ref_id,
                $order->total_amount,
                $order->created_at
            );
        }
    }

    public function deleted(SalesOrder $order): void
    {
        if ($order->status === 'approved' && $order->user_ref_id) {
            $this->salesTargetService->recordAchievement(
                $order->user_ref_id,
                -1 * $order->total_amount,
                $order->updated_at ?? $order->created_at
            );
        }
    }

    public function restored(SalesOrder $order): void
    {
        if ($order->status === 'approved' && $order->user_ref_id) {
            $this->salesTargetService->recordAchievement(
                $order->user_ref_id,
                $order->total_amount,
                $order->updated_at ?? $order->created_at
            );
        }
    }

    public function forceDeleted(SalesOrder $order): void
    {
        //
    }
}