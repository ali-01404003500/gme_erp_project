<?php

namespace Modules\Account\Services;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\ { Account, AccountControl, AccountGroup, AccountSubsidiary };

class DataService {

    function getAccountData($arr)
    {
        $data = [];

        foreach ($arr as $model)
        {
            $data[$model] = $this->$model();
        }

        return $data;
    }

    function accountGroups()
    {
        return AccountGroup::query()->orderBy('name')->select('id', 'name')->get();
    }

    function accountControls($account_group_id = null)
    {
        return AccountControl::query()
            ->when($account_group_id != null, function ($q) use($account_group_id) {
                $q->where('account_group_id', $account_group_id);
            })
            ->orderBy('name')
            ->select('id', 'name')
            ->get();
    }

    function accountSubsidiaries($account_control_id = null)
    {
        return AccountSubsidiary::query()
            ->when($account_control_id != null, function ($q) use($account_control_id) {
                $q->where('account_control_id', $account_control_id);
            })
            ->orderBy('name')
            ->select('id', 'name')
            ->get();
    }

    function accounts()
    {
        return Account::query()->active()->orderBy('name')->withCount(['transactions as balance' => function($q) {
            return $q->select(DB::Raw('SUM(amount)'));
        }])->get();
    }
}
