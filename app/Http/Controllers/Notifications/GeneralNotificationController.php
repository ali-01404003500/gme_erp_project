<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OtpVerifyController;
use App\Http\Controllers\Purchase\RequisitionController;
use App\Models\Notifications\GeneralNotification;
use App\Models\OtpVerification;
use App\Services\Notifications\GeneralNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeneralNotificationController extends Controller
{

    /**
     * Service variable
     *
     * @var GeneralNotificationService
     */
    private $service; 
    function __construct(GeneralNotificationService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['generalNotifications'] = $this->service->getAll();

        return view("notifications.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->store($validate);
        return redirect()->route('notifications.general-notifications.index')->with('success', 'GeneralNotification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['generalNotification'] = $this->service->show($id);

        return view("generalNotifications.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeneralNotification $generalNotification)
    {
        $data['generalNotification'] = $generalNotification;
        //
        return view("generalNotifications.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GeneralNotification $generalNotification)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($generalNotification, $validate);

        return redirect()->route('notifications.general-notifications.index')->with('success', 'GeneralNotification updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneralNotification $generalNotification)
    {
        $this->service->delete($generalNotification);
        return redirect()->route('notifications.general-notifications.index')->with('success', 'GeneralNotification deleted successfully.');
    }

    public function getNotificationCount()
    {

        $host = $this->service->getNotificationCount();
        return response()->json($host);
    }

    public function getNotifications()
    {
        $host = $this->service->getNotifications();
        return response()->json($host);
    }


    public function notificationAction($id){
        $generalNotification = GeneralNotification::find($id);

        $generalNotification->update([
            'status' => 2
        ]);
        $this->service->updateNotificationCount();

        if( $generalNotification->action ) {
            $action = json_decode($generalNotification->action, true);
            if(isset($action['type']) && $action['type'] == 'redirect' && isset($action['url'])) {
                return redirect($action['url']);
            }
            $controllerClass = $action['controller'];
            $method = $action['method'];
            $parameters = $action['params'];

            try {
                $controller = app()->make($controllerClass); 
                return $controller->callAction($method, $parameters);
            } catch (\Throwable $e) {
                dd($e);
                // Ignore any error
                return redirect()->back()->with('error', 'Something went wrong while performing the action on the notification.');
            }
        }

        session()->flash('success', 'Successfully completed the action on the notification.');
        echo "<script>if (window.history.length > 1) { window.history.back(); } else { window.close(); }</script>";
        exit;
    }



    public function optVerificationRequest(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'remark' => 'nullable|string|max:250',
            'additional_data' => 'nullable|string'
        ]);

        // dd($validated);
        try {
            // Generate unique request ID
            $requestId = \Illuminate\Support\Str::uuid()->toString();
            
            // Create verification data
            $verificationData = [
                'type' => $validated['type'] ?? 'default',
                'pending_ids' => $validated['ids'],
                'remark' => $validated['remark'] ?? null,
                'created_by' => auth()->user()->id,
                'created_at' => now(),
                'status' => 'pending',
                'additional_data' => $validated['additional_data']
            ];

            // Store in cache for 1 hour
            Cache::put(
                "otp_verification:{$requestId}",
                $verificationData,
                now()->addHour()
            );

            // Create notification
            $notificationData = [
                'title' => 'OTP Verification Request',
                'type' => 'otp_verification_request',
                'description' => 'New OTP verification request pending approval',
                'action' =>   $this->service->actionBuilder(GeneralNotificationController::class, 'handleOtpVerification', [$requestId]),
            ];
            //$this->service->store($notificationData, [1]);
            // dd($notificationData);

            $this->handleOtpVerification($requestId);

            return response()->json([
                'success' => true,
                'request_id' => $requestId
            ]);
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('OTP Verification Request Error: ' . $e->getMessage());
            // Handle error
            return response()->json([
                'success' => false,
                'message' => 'Failed to create OTP verification request'
            ], 500);
        }
    }

    public function getOtpVerificationStatus(Request $request)
    {
        $requestId = $request->input('request_id');
        $verificationData = Cache::get("otp_verification:{$requestId}");
        if (!$verificationData) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request id'
            ], 404);
        }

        if($verificationData['status'] == 'pending'){
            return response()->json([
                'success' => true,
                'status' => 'pending'
            ]);
        }

        $verifyData = $verificationData;
        if($verifyData['approved_ids']??false){
            $verifyData['approved'] = OtpVerification::whereIn('id', $verifyData['approved_ids'])->get();
        }

        if($verifyData['denied_ids']??false){
            $verifyData['denied'] = OtpVerification::whereIn('id', $verifyData['denied_ids'])->get();
        }

        return response()->json([
            'success' => true,
            'status' => $verificationData['status'],
            'data' =>  (object) $verifyData
        ]);
    }

    public function handleOtpVerification(string $requestId)
    {
        $permittedUsers = $this->service->getPermittedUsers('verification.verification-requests');
        foreach ($permittedUsers as $user) {
            $requests = Cache::get("user_verifications:{$user}", []);
            $requests[] = $requestId;
            Cache::put("user_verifications:{$user}", $requests, now()->addHours(1));
        }

        $notificationData = [
                'title' => 'OTP Verification Request Assigned',
                'type' => 'otp_verification_assignment',
                'description' => "A new OTP verification request has been assigned to you.",
                'action' => $this->service->actionBuilder(OtpVerifyController::class, 'showVerificationForm', []),
            ];
        $this->service->store($notificationData, $permittedUsers??[1]);


       // echo "<script>if (window.history.length > 1) { window.history.back(); } else { window.close(); }</script>";
       // exit;
    }
}
