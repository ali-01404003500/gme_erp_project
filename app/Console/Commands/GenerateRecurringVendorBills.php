<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\Account\Models\VendorBill\GeneratedVendorBill;
use Modules\Account\Models\VendorBill\VendorBillSetting;

class GenerateRecurringVendorBills extends Command
{
     protected $signature = 'bills:generate';
    protected $description = 'Automatically generate recurring vendor bills based on schedule settings';

    public function handle()
    {
        $today = now()->format('Y-m-d');
        $runningSettings = VendorBillSetting::where('status', 'Running')->get();

        foreach ($runningSettings as $setting) {
            try {
                // Get last generated bill
                $lastBill = GeneratedVendorBill::where('setting_id', $setting->id)
                    ->latest('bill_date')
                    ->first();

                // Determine next due date
                $nextDueDate = $this->getNextDueDate($setting, $lastBill);

                $this->info("Next due date for `{$setting->title}` (Setting ID: {$setting->id}): {$nextDueDate}");

                // If today is the due date → generate bill
                if (Carbon::parse($nextDueDate)->lte(Carbon::parse($today))) {
                    $this->generateBill($setting);
                }
            } catch (Exception $e) {
                Log::error("Failed to generate bill for setting ID {$setting->id}: " . $e->getMessage());
            }
        }

        $this->info(count($runningSettings) . '  Recurring vendor bills generated successfully.');
    }

    /**
     * Calculate next due date based on last bill or start_date
     */
    private function getNextDueDate(VendorBillSetting $setting, $lastBill = null)
    {
        if (!$lastBill) {
            // First bill: use start_date
            return $setting->start_date;
        }

        $date = Carbon::parse($lastBill->bill_date);

        return match ($setting->schedule_type) {
            'Daily' => $date->addDays($setting->schedule_value)->format('Y-m-d'),
            'Monthly' => $date->addMonths($setting->schedule_value)->format('Y-m-d'),
            'Yearly' => $date->addYears($setting->schedule_value)->format('Y-m-d'),
            'Static' => null, // Only once
            default => null
        };
    }

    /**
     * Generate a new bill from the setting
     */
    private function generateBill(VendorBillSetting $setting)
    {
        $billId = 'BILL-' . now()->format('Y') . '-' . str_pad((GeneratedVendorBill::withTrashed()->max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT);
        

        try {
            $bill = GeneratedVendorBill::create([
                'setting_id' => $setting->id,
                'bill_id' => $billId,
                'bill_for_id' => $setting->bill_for_id,
                'bill_for_type' => $setting->bill_for_type,
                'bill_date' => now()->format('Y-m-d'),
                'amount' => $setting->amount,
            ]);
        } catch (Exception $e) {
            // dd($e);
            $this->error("Failed to generate bill for setting ID {$setting->id}: " . $e->getMessage());
        }


       $this->info("Generated bill: {$billId} for {$setting->title} (Setting ID: {$setting->id})");
    }
}
