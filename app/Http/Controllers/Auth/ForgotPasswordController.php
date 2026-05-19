<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
     private $smsService;

    function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    // Show forget password form
    public function showForgetPasswordUserCheckForm()
    {
        return view('auth.forget-password-user-check'); 
    }

    
    // Verify email
    public function verifyEmail(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:email',
        ]);   

        try {

            $user = User::where('email', $request->email)->first(); 
            return redirect()->route('password.request', ['user_id' => $user->id])->with('success', 'Password reset successfully. Please login with your new credentials');

        } catch (\Exception $e) {
            return back()->with('error', 'Invalid Email. Please try again')->withInput();
        }
 

    }
 
    public function showForgetPasswordForm($user_id)
    {
        $user = User::with('employee')->find($user_id);
   
        if (!$user || !$user->employee || $user->employee->status === 0) {
            return redirect()->back()->with('error', 'User is inactive or not found');
        }
        $data = (object)  [
            'user_id'      => $user->id,
            'email'        => $user->email,
            'office_phone' => $user->employee->office_phone ?? null,
        ];

        return view('auth.forget-password', compact('data'));
        
    }


    // Send OTP to phone number
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|numeric|digits:11',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid phone number',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find user by employee phone number
        $user = User::where('user_status', 'active') 
            ->whereHas('employee', function($query) use ($request) {
            $query->where('office_phone', $request->phone_number);
        })->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found with this phone number'
            ], 404);
        }

        // Generate 4 digit OTP
        $otp = rand(1000, 9999);

        // Store OTP in remember_token field
        $user->remember_token = $otp;
        $user->save();

        // Send OTP via SMS
        $employeeContact = $request->phone_number;

        if (substr($employeeContact, 0, 2) === '01') {
            $employeeContact = '880' . substr($employeeContact, 1);
        }

        $message = "Your OTP for password reset is: {$otp}. This OTP will expire in 5 minutes.";

        try {
            $sent = $this->smsService->send($employeeContact, $message);

            if ($sent) {
                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent to employee successfully',
                    'user_id' => $user->id
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP: ' . $e->getMessage()
            ]);
        }
    }
 

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|numeric|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP format',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);

        if ($user->remember_token != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully'
        ]);
    }

    // Show reset password form
    public function showResetPasswordForm(Request $request)
    {
        $userId = $request->user_id;
        $otp = $request->otp;
        
        return view('auth.reset-password', compact('userId', 'otp'));
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|numeric|digits:4',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::find($request->user_id);

        // Verify OTP again
        if ($user->remember_token != $request->otp) {
            return back()->with('error', 'Invalid OTP. Please try again')->withInput();
        }

        // Update email and password
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->remember_token = null; // Clear OTP
        $user->save();

        return redirect()->route('login')->with('success', 'Password reset successfully. Please login with your new credentials');
    }
}