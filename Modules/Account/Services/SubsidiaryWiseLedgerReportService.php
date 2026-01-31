<?php


namespace Modules\Account\Services;


use Illuminate\Http\Request;
use Modules\Account\Models\Account;
use Modules\Account\Models\Transaction;

class SubsidiaryWiseLedgerReportService
{
    public function getLedger(Request $request)
    {
        $data['accounts'] = Account::query()
                            ->where('account_subsidiary_id', $request->account_subsidiary_id ?? -1)
                            ->orderBy('account_group_id')
                            ->orderBy('account_control_id')
                            ->orderBy('account_subsidiary_id')
                            ->orderBy('name')
                            ->get();

        $data['accountTransactions'] = Account::query()
                                    ->whereIn('id', $request->accounts ?? [])
                                    ->with(['transaction_items' => function ($q) use ($request) {
                                        $q->with('transactionable')
                                            ->searchByField('company_id')
                                            ->where('date', '>=', $request->from)
                                            ->where('date', '<=', $request->to);
                                    }])
                                    ->orderBy('account_group_id')
                                    ->orderBy('account_control_id')
                                    ->orderBy('account_subsidiary_id')
                                    ->orderBy('name')
                                    ->get();

        return $data;
    }
}
