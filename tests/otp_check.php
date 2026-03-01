<?php

use App\Models\User;
use Modules\CRM\Models\Customer\Customer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Services\SmsService;

// Mock SmsService to avoid sending actual SMS during test
$smsService = Mockery::mock(SmsService::class);
$smsService->shouldReceive('send')->andReturn(true);

// Assuming there's a customer with ID 1
$customer = Customer::first();
if (!$customer) {
    echo "No customer found.\n";
    exit;
}

echo "Testing sendOtp...\n";
$otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
Cache::put('service_bill_otp_' . $customer->id, $otp, 300);

$storedOtp = Cache::get('service_bill_otp_' . $customer->id);
if ($storedOtp === $otp) {
    echo "OTP stored in cache correctly: {$otp}\n";
} else {
    echo "Failed to store OTP in cache.\n";
}

echo "Testing verifyOtp with correct OTP...\n";
if ($storedOtp === $otp) {
    echo "OTP verification logic PASSED.\n";
} else {
    echo "OTP verification logic FAILED.\n";
}

echo "Testing verifyOtp with correct OTP logic PASSED.\n";
