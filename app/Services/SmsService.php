<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $baseUrl = 'https://apibd.rmlconnect.net:8443/bulksms/personalizedbulksms';
    protected ?string $username;
    protected ?string $password; 
    protected ?string $source;
    /*https://apibd.rmlconnect.net:8443/bulksms/personalizedbulksms?username=GMELBDENT&password=8op7jCng&source=8801894953797&destination=xxxxx&message=xxxxx*/
    public function __construct()
    {
        $this->username = config('services.sms.username');
        $this->password = config('services.sms.password');
        $this->source = config('services.sms.source');
    }

    public function send(string $destination, string $message): bool
    {
        try {
            $response = Http::get($this->baseUrl, [
                'username' => $this->username,
                'password' => $this->password,
                'source' => $this->source,
                'destination' => $destination,
                'message' => $message
            ]);
            Log::info("SMS sent to {$destination}", [
                'username' => $this->username,
                'password' => $this->password,
                'source' => $this->source,
                'destination' => $destination,
                'message' => $message,
                'baseUrl' => $this->baseUrl
            ]);

            if ($response->successful()) {
                Log::info("SMS sent to {$destination}", ['response' => $response->body()]);
                return true;
            }

            Log::error("SMS sending failed to {$destination}", ['response' => $response->body()]);
            return false;
        } catch (\Exception $e) {
            Log::error("SMS sending exception to {$destination}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendBulk(array $destinations, string $message): array
{
    Log::info("Starting bulk SMS", [
        'count' => count($destinations),
        'destinations' => $destinations
    ]);
    
    $results = [];
    
    // Filter out empty/invalid numbers
    $destinations = array_filter($destinations, function($destination) {
        return !empty($destination) && is_string($destination);
    });
    
    Log::info("After filtering", ['count' => count($destinations)]);
    
    foreach ($destinations as $index => $originalDestination) {
        $formattedDestination = $originalDestination;
        
        if (substr($formattedDestination, 0, 2) === '01') {
            $formattedDestination = '880' . substr($formattedDestination, 1);
        }
        
        Log::info("Sending to", [
            'index' => $index,
            'original' => $originalDestination,
            'formatted' => $formattedDestination
        ]);
        
        $results[$originalDestination] = $this->send($formattedDestination, $message);
    }
    
    Log::info("Bulk SMS completed", ['results' => $results]);

    return $results;
}
}