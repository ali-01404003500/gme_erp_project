<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SmsInfo;
use Carbon\Carbon;
use App\Services\SmsService; // তোমার service namespace

class SendPendingSms extends Command
{
    protected $signature = 'sms:send-pending';
    protected $description = 'Send pending SMS whose send_time is due';

    private $smsService;

    public function __construct(SmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    public function handle()
    {
        $now = Carbon::now();

        // Pending SMS fetch
        $pendingSms = SmsInfo::where('sms_status', 'pending')
            ->where('sms_send_time', '<=', $now)
            ->get();

        foreach ($pendingSms as $sms) {

            try {
                // Existing SmsService use
                $sent = $this->smsService->send($sms->sms_to, $sms->sms_text);

                if ($sent) {
                    $sms->update(['sms_status' => 'Sent']);
                    $this->info("SMS ID {$sms->id} sent successfully.");
                } else {
                    $sms->update(['sms_status' => 'Failed']);
                    $this->error("SMS ID {$sms->id} failed to send.");
                }

            } catch (\Exception $e) {
                $sms->update(['sms_status' => 'Failed']);
                $this->error("SMS ID {$sms->id} failed: ".$e->getMessage());
            }

        }
    }
}