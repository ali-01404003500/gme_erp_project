<?php

namespace App\Http\Controllers;

use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AllVerificationTabController extends Controller
{


    public function showAllVerificationTab(Request $request=null)
    {  
        return view('verification.all-verification-tab');
    }
    public function mfsVerification(Request $request=null)
    {  
        return view('account.mfs-verifications');
    }

}