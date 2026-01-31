<?php

namespace Modules\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use Modules\Account\Models\AccountSetup;

class AccountSetupController extends Controller
{
    

    public function index()
    {
        

        $data['data'] = AccountSetup::query()->orderBy('name')->get();

        return view('Account::setup.account-setups.index', $data);
    }
}
