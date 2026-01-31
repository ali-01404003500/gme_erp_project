<?php

namespace Modules\Account\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Account\Models\Account;
use Modules\Account\Models\AccountControl;
use Modules\Account\Models\AccountSubsidiary;
use Modules\Account\Models\FundTransfer;
use Modules\Account\Models\Voucher;

class IndexDataService
{
    public function getAccountControlData()
    {
        return AccountControl::query()
            ->with('accountGroup')
            ->orderBy('account_group_id')
            ->orderBy('name')
            ->get();
    }

    public function getAccountSubsidiaryData()
    {
        return AccountSubsidiary::query()
            ->with('accountGroup', 'accountControl')
            ->orderBy('account_group_id')
            ->orderBy('account_control_id')
            ->orderBy('name')
            ->get();
    }

    public function getAccountData()
    {
        return Account::query()
            ->with('accountGroup', 'accountControl', 'accountSubsidiary')
            ->orderBy('account_group_id')
            ->orderBy('account_control_id')
            ->orderBy('account_subsidiary_id')
            ->orderBy('name')
            ->get();
    }

    public function getVoucherData($data): LengthAwarePaginator
    {
        return Voucher::query()
            ->where('voucher_type', $data)
            ->orderBy('id', 'DESC')
            ->paginate(30);
    }

    public function getFundTransferData(): LengthAwarePaginator
    {
        return FundTransfer::query()
            ->orderByDesc('id')
            ->orderBy('date', 'DESC')
            ->paginate(30);
    }
}
