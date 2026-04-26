<?php

namespace App\Http\Controllers;

use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OtpVerifyController extends Controller
{
    //

    
    public function showVerificationForm(Request $request=null)
    {

        $user = auth()->user()->id ?? null;
        $requestsIds = Cache::get("user_verifications:{$user}", []);
        $requests = collect($requestsIds)->map(function ($id) {
            $optVerification = Cache::get("otp_verification:{$id}");
            if (!$optVerification) {
                return null;
            }
            $optVerification['id'] = $id;
             $optVerification['pending'] = OtpVerification::whereIn('id', $optVerification['pending_ids'])->get();
             $optVerification['created_by'] = User::find($optVerification['created_by']);
            return (object)$optVerification;
        });
        // return response()->json(['requests' => $requests]);

        $data['verificationRequests']= $requests;
        return view('verification.verification-requests', $data);
    }
 


    public function verifyOtp(Request $request){
        // dd($request->all());
        $status = $request->action === 'accept' ? 'approved' : 'denied';

        OtpVerification::whereIn('id', $request->pending_ids)
            ->update([
                'status' => $status
            ]);

        // $otpVerification = OtpVerification::find($request->verification_request_id);
        $user_id  = auth()->user()->id;
        $requestsIds  = Cache::get("user_verifications:{$user_id}");


        $verificationData = Cache::get("otp_verification:{$request->verification_request_id}");
        $verificationData['status'] = 'responded';
        $verificationData['responded_remarks'] = $request->remarks;
        if ($request->action === 'accept') {
            OtpVerification::whereIn('id', $request->pending_ids)
            ->update([
                'accepted_by' => $user_id,
                'accepted_at' => now(),
                'accepted_data' => json_encode($verificationData)
            ]);
            $verificationData['approved_ids'] = array_merge($verificationData['approved_ids']??[], $request->pending_ids);
        }
        if ($status === 'denied') {
            $verificationData['denied_ids'] = array_merge($verificationData['denied_ids']??[], $request->pending_ids);
        }
        // dd($verificationData);
        Cache::put("otp_verification:{$request->verification_request_id}", $verificationData, now()->addHours(1));
     

        // $otpVerification->update ([
        //     'approved_otp'=> $verificationData
        // ]);
        // if( $status === 'approved'){
            // unassign verification request
        $requestsIds = array_filter($requestsIds, function ($id) use ($request) {
            return $id != $request->verification_request_id;
        });
        Cache::put("user_verifications:{$user_id}", $requestsIds, now()->addHours(1));
        // }



        // dd($verificationData);

        return response()->json(['success' => true]);
    }


    public function createOtp(Request $request)
    {
        $validatedData = $request->validate([
            'data' => 'required|string',
        ]);

        $data = json_decode($request->data, true);

        if (isset($data['id'])) {
            $otpVerification = OtpVerification::find($data['id']);
            if ($otpVerification) {
                $otpVerification->update($data);
            } else {
                $otpVerification = OtpVerification::create($data);
            }
        } else {
            $otpVerification = OtpVerification::create($data);
        }

        return response()->json($otpVerification, 201);
    }

    public function updateOtp(Request $request)
    {
        $verificationIds = $request->verification_ids;
        $updatedVerifications = [];

        foreach ($verificationIds as $id) {
            if ($id) {
                // Update verification status in database
                $updatedVerifications[] = OtpVerification::find($id);
            }
        }

        return response()->json($updatedVerifications);
    }


    public function deleteOtp(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);

        $otpVerification = OtpVerification::find($validatedData['id']);

        if ($otpVerification) {
            $otpVerification->delete();
            return response()->json(['success' => true, 'message' => 'OTP verification deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'OTP verification not found.'], 404)
        ;
    }

}
